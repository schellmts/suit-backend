<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisteredUserOwnerRequest;
use App\Models\Account;
use App\Models\Network;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserNetwork;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisteredUserOwnerController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisteredUserOwnerRequest $request)
    {
        DB::beginTransaction();
        try {
            $network = Network::create([
                'name' => $request->name . ' Network',
                'description' => null,
            ]);

            $user = new User();
            $user->fill($request->all());
            $user->password = Hash::make($request->password);
            $user->saveOrFail();

            UserNetwork::create([
                'user_id' => $user->id,
                'network_id' => $network->id,
                'type' => UserNetwork::OWNER,
            ]);

            $account = Account::create([
                'name' => $request->name . ' Account',
                'network_id' => $network->id,
                'description' => null,
                'active' => Account::ACTIVE,
                'status' => Account::AUTHORIZED
            ]);

            $role = Role::where('name', '=', 'host')->firstOrFail();

            UserAccount::create([
                'user_id' => $user->id,
                'account_id' => $account->id,
                'role_id' => $role->id,
                'add_by_user_id' => $user->id,
                'removed_by_user_id' => null
            ]);

            event(new Registered($user));

            DB::commit();
            return response()->json(['message' => 'Verification email sent to user']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Create user error: ' . $e->getMessage());
            return response()->json(['message' => 'Create user error: ' . $e->getMessage()], 500);
        }
    }
}
