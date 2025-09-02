<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhysicalPersonRequest;
use App\Models\PhysicalPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PhysicalPersonController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view', [PhysicalPerson::class, $request->route('account')]); //verifica se o usuario tem permissa´para fazer get
        /*
       Ex: /accounts/5/customers entao ira buscar o Account com o id 5 e injetar no $request->route('account').
       */

        $account = $request->route('account');
        return response()->json($account->physicalPersons()->get());
    }

    public function store(PhysicalPersonRequest $request)
    {
        Gate::authorize('create', [PhysicalPerson::class, $request->route('account')]);

        if ($request->account_id != $request->route('account')->id) {
            return response()->json("Account IDs do not match", 404);
        }

        $data = $request->all();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $physicalPerson = PhysicalPerson::create($data);

        return response()->json($physicalPerson);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        Gate::authorize('view', [PhysicalPerson::class, $request->route('account')]);

        return $request->route('physicalPerson');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PhysicalPersonRequest $request)
    {
        Gate::authorize('update', [PhysicalPerson::class, $request->route('account')]);

        if ($request->account_id != $request->route('account')->id) {
            return response()->json("Account IDs do not match", 404);
        }

        $physicalPerson = $request->route('physicalPerson');
        $physicalPerson->fill($request->all());
        $physicalPerson->account_id = $request->route('account')->id;
        $physicalPerson->updated_by = $request->user()->id;
        $physicalPerson->update();

        return response()->json($physicalPerson);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Gate::authorize('delete', [PhysicalPerson::class, $request->route('account')]);

        $physicalPerson = $request->route('physicalPerson');
        return $physicalPerson->delete();
    }
}
