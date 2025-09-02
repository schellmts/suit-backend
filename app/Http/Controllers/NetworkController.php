<?php

namespace App\Http\Controllers;

use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json($request->user()->networks()->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(Network $network)
    {
        return response()->json($network);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Network $network)
    {
        Gate::authorize('update', [Network::class, $network]);

        $request->validate([
            'name' => 'required | string | max:255',
            'description' => 'required | string | max:255'
        ]);

        $network->name = $request->name;
        $network->description = $request->description;
        $network->update();

        return response()->json($network);
    }
}
