<?php

namespace App\Http\Controllers;

use App\Http\Requests\JuridicalPersonRequest;
use App\Models\JuridicalPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class JuridicalPersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view', [JuridicalPerson::class, $request->route('account')]);

        $account = $request->route('account');
        return response()->json($account->juridicalPersons()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JuridicalPersonRequest $request)
    {
        Gate::authorize('create', [JuridicalPerson::class, $request->route('account')]);

        if ($request->account_id != $request->route('account')->id) {
            return response()->json("Account IDs do not match", 404);
        }

        $data = $request->all();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;



        $juridicalPerson = JuridicalPerson::create($data);


        return response()->json($juridicalPerson);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        Gate::authorize('view', [JuridicalPerson::class, $request->route('account')]);

        return $request->route('juridicalPerson');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JuridicalPersonRequest $request)
    {
        Gate::authorize('update', [JuridicalPerson::class, $request->route('account')]);

        if ($request->account_id != $request->route('account')->id) {
            return response()->json("Account IDs do not match", 404);
        }

        $juridicalPerson = $request->route('juridicalPerson');
        $juridicalPerson->fill($request->all());
        $juridicalPerson->account_id = $request->route('account')->id;
        $juridicalPerson->updated_by = $request->user()->id;
        $juridicalPerson->update();

        return response()->json($juridicalPerson);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Gate::authorize('delete', [JuridicalPerson::class, $request->route('account')]);

        $juridicalPerson = $request->route('juridicalPerson');
        return $juridicalPerson->delete();
    }
}
