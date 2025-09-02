<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AccountService;
use App\Http\Requests\AccountStoreRequest;
use App\Models\Account;
use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    public function __construct(public AccountService $account_service)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::define('viewAny', function (User $user, $model, $network) {
            return $user->hasPermissionTo('account-viewAny', $network);
        });


        return response()->json($request->user()->accounts()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountStoreRequest $request, Network $network)
    {
        Gate::authorize('create', [Account::class, $request->network]);

        return response()->json($this->account_service->create($request->all(), $network));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $account = $request->route('account');

        Gate::authorize('view', [Account::class, $request->route('account')]);

        return response()->json($request->user()->accounts()->find($account->id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $account = $request->route('account');

        Gate::authorize('update', [Account::class, $request->route('account')]);

        return response()->json($this->account_service->update($account, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $account = $request->route('account');

        Gate::authorize('delete', [Account::class, $request->route('account')]);

        return response()->json($request->user()->accounts()->find($account->id)->delete());
    }
}
