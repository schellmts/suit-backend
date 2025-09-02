<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view', [Customer::class, $request->route('account')]);

        $account = $request->route('account');

        return response()->json($account->customers()->with('users')->get());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request)
    {
        Gate::authorize('create', [Customer::class, $request->route('account')]);

        if($request->account_id != $request->route('account')->id){
            return response()->json("Account IDs do not match", 404);
        }
        $customer = Customer::create($request->all());

        return response()->json($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        Gate::authorize('view', [Customer::class, $request->route('account')]);

        return $request->route('customer');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerUpdateRequest $request)
    {
        Gate::authorize('update', [Customer::class, $request->route('account')]);

        if($request->account_id != $request->route('account')->id){
            return response()->json("Account IDs do not match", 404);
        }
        $customer = $request->route('customer');
        $customer->fill($request->all());
        $customer->account_id = $request->route('account')->id;
        $customer->update();

        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Gate::authorize('delete', [Customer::class, $request->route('account')]);

        $customer = $request->route('customer');
        return $customer->delete();
    }
}
