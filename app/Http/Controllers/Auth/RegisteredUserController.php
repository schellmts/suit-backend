<?php

namespace App\Http\Controllers\Auth;

use App\Events\InvitationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptInviteRequest;
use App\Http\Requests\Auth\RegisteredUserRequest;
use App\Mail\InvitationMail;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Invitation;
use App\Models\Network;
use App\Models\Role;
use App\Models\TicketAgents;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserNetwork;
use App\Services\UserAccountService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use \Illuminate\Support\Str;

class RegisteredUserController extends Controller
{

    function __construct(public UserAccountService $userAccountService)
    {
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisteredUserRequest $request): JsonResponse
    {
        $account = $request->route('account');

        if ($request->type === UserNetwork::CONTRIBUTOR) {
            if (!$this->userAccountService->validateQuantityLicense($account->id)) {
                return response()->json(['message' => 'Insufficient number of available licenses'], 403);
            }
        }

        $role = null;

        if($request->type == UserNetwork::CUSTOMER){
            if($request->customer_id == null){
                return response()->json(['message' => 'If type customer you need send customer_id'], 404);
            } else {
                if(!$request->route('account')->customers->firstWhere('id', $request->customer_id)){
                    return response()->json(['message' => 'The customer reported not linked to this account'], 404);
                }
                $role = $request->route('account')->roles->firstWhere('name', 'customer');
            }
        }

        if($request->type == UserNetwork::SUPPLIER){
            if($request->supplier_id == null){
                return response()->json(['message' => 'If type supplier you need send supplier_id'], 404);
            } else {
                if(!$request->route('account')->suppliers->firstWhere('id', $request->supplier_id)){
                    return response()->json(['message' => 'The supplier reported not linked to this account'], 404);
                }
                $role = $request->route('account')->roles->firstWhere('name', 'supplier');
            }
        }

        $invite = Invitation::updateOrCreate(['email' => $request->email], [
            'invited_by_user_id' => $request->user()->id,
            'account_id' => $request->route('account')->id,
            'role_id' => $role->id ?? $request->role_id,
            'customer_id' => $request->customer_id ?? null,
            'supplier_id' => $request->supplier_id ?? null,
            'email' => $request->email,
            'type' => $request->type,
            'token' => Str::uuid(),
            'expires_at' => now()->addDays(3),
        ]);

        InvitationEvent::dispatch($invite);

        return response()->json($invite);
    }

//    function accept(AcceptInviteRequest $request): JsonResponse
//    {
//        if (!Invitation::where('email', $request->email)->where('token', $request->token)->exists()) {
//            return response()->json(['message' => "Token invalid or expired"], 404);
//        }else{
//            if(Invitation::where('email', $request->email)->where('token', $request->token)->first()->isExpired()){
//                return response()->json(['message' => "Token invalid or expiredd"], 404);
//            }
//        }
//
//        DB::beginTransaction();
//        try {
//            $user = new User();
//            $user->fill($request->all());
//            ds($user);
//            $user->password = Hash::make($request->password);
//            $user->saveOrFail();
//            $user->markEmailAsVerified();
//
//            $invitation = Invitation::firstWhere('token', $request->token);
//            $account = Account::find($invitation->account_id);
//
//            UserNetwork::create([
//                'user_id' => $user->id,
//                'network_id' => $account->network_id,
//                'type' => UserNetwork::$TYPES[$invitation->type],
//            ]);
//
//            UserAccount::create([
//                'user_id' => $user->id,
//                'account_id' => $account->id,
//                'role_id' => $invitation->role_id,
//                'add_by_user_id' => $invitation->invited_by_user_id,
//                'removed_by_user_id' => null
//            ]);
//
//            switch($invitation->type){
//                case UserNetwork::CUSTOMER:
//                    $user->customers()->attach($invitation->customer_id);
//                    break;
//
//                case UserNetwork::SUPPLIER:
//                    $user->suppliers()->attach($invitation->supplier_id);
//                    break;
//
//                default:
//                    break;
//            }
//
//            $invitation->delete();
//
//            DB::commit();
//            return response()->json($user);
//
//        } catch (\Exception $e) {
//            return response()->json($e);
//            DB::rollBack();
//        }
//    }

    public function accept(AcceptInviteRequest $request): JsonResponse
    {
        if (!Invitation::where('email', $request->email)->where('token', $request->token)->exists()) {
            return response()->json(['message' => "Token invalid or expired"], 404);
        }

        $invitation = Invitation::firstWhere('token', $request->token);
        $account = Account::find($invitation->account_id);

        if ($invitation->type === 'contributor') {
            $license = $account->subscriptions()
                ->with('items')
                ->get()
                ->flatMap->items
                ->firstWhere('stripe_product', env('LICENSE_STRIPE_ID'));

            $usedLicenses = UserAccount::where('account_id', $invitation->account_id)
                ->whereHas('user.networks', function ($query) use ($account) {
                    $query->where('network_id', $account->network_id)
                        ->where('type', UserNetwork::CONTRIBUTOR);
                })
                ->count();


            if ($usedLicenses >= $license->quantity) {
                return response()->json(['message' => 'Insufficient number of available licenses'], 403);
            }
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user->fill($request->all());
            $user->password = Hash::make($request->password);
            $user->saveOrFail();
            $user->markEmailAsVerified();

            UserNetwork::create([
                'user_id' => $user->id,
                'network_id' => $account->network_id,
                'type' => UserNetwork::$TYPES[$invitation->type],
            ]);

            UserAccount::create([
                'user_id' => $user->id,
                'account_id' => $account->id,
                'role_id' => $invitation->role_id,
                'add_by_user_id' => $invitation->invited_by_user_id,
                'removed_by_user_id' => null
            ]);

            if ($invitation->type === 'contributor') {
                TicketAgents::firstOrCreate([
                   'user_id' => $user->id,
                   'account_id' => $account->id,
                ]);
            }

            switch ($invitation->type) {
                case UserNetwork::CUSTOMER:
                    $user->customers()->attach($invitation->customer_id);
                    break;

                case UserNetwork::SUPPLIER:
                    $user->suppliers()->attach($invitation->supplier_id);
                    break;
            }

            $invitation->delete();

            DB::commit();
            return response()->json($user);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e);
        }
    }

}
