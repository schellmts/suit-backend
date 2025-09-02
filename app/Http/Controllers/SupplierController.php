<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view', [Supplier::class, $request->route('account')]);

        $account = $request->route('account');
        return response()->json($account->suppliers()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierStoreRequest $request)
    {
        Gate::authorize('create', [Supplier::class, $request->route('account')]);

        if($request->account_id != $request->route('account')->id){
            return response()->json("Account IDs do not match", 404);
        }
        $supplier = Supplier::create($request->all());

        return response()->json($supplier);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        Gate::authorize('view', [Supplier::class, $request->route('account')]);

        return $request->route('supplier');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierUpdateRequest $request)
    {
        Gate::authorize('update', [Supplier::class, $request->route('account')]);

        if($request->account_id != $request->route('account')->id){
            return response()->json("Account IDs do not match", 404);
        }
        $supplier = $request->route('supplier');
        $supplier->fill($request->all());
        $supplier->account_id = $request->route('account')->id;
        $supplier->update();

        return response()->json($supplier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Gate::authorize('delete', [Supplier::class, $request->route('account')]);

        $supplier = $request->route('supplier');
        return $supplier->delete();
    }
}
