<?php

namespace App\Http\Controllers;

use App\Http\Requests\CostCenterStoreRequest;
use App\Http\Requests\CostCenterUpdateRequest;
use App\Models\Account;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CostCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $network = $request->network;
        Gate::authorize('viewAny', [CostCenter::class, $network]);
        return $network->costCenters()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CostCenterStoreRequest $request)
    {
        $network = $request->network;
        Gate::authorize('create', [CostCenter::class, $network]);

        if($network->id != $request->network_id){
            return response()->json(['message' => 'Network IDs do not match'], 404);
        }
        return CostCenter::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        Gate::authorize('create', [CostCenter::class, $request->network]);

        return $request->route('cost_center');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CostCenterUpdateRequest $request)
    {
        Gate::authorize('update', [CostCenter::class, $request->network]);

        $cost_center = $request->route('cost_center');
        $cost_center->fill($request->all());
        $cost_center->network_id = $request->network->id;
        $cost_center->update();
        return $cost_center;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Gate::authorize('delete', [CostCenter::class, $request->network]);

        $cost_center = $request->route('cost_center');
        $cost_center->delete();
    }
}
