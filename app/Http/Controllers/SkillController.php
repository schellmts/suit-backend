<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillStoreRequest;
use App\Models\Account;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $account = $request->route('account');

        Gate::authorize('viewAny', [Skill::class, $request->route('account')]);

        return response()->json($account->skills()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SkillStoreRequest $request)
    {
        $account = $request->route('account');

        Gate::authorize('create', [Skill::class, $request->route('account')]);

        $skill = new Skill();
        $skill->fill($request->all());
        $skill->account_id = $account->id;
        $skill->save();

        return $skill;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $account = $request->route('account');
        $skill = $request->route('skill');

        Gate::authorize('view', [Skill::class, $request->route('account')]);

        return $account->skills()->findOrFail($skill->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SkillStoreRequest $request)
    {
        $account = $request->route('account');
        $skill = $request->route('skill');

        Gate::authorize('update', [Skill::class, $request->route('account')]);

        $skill->fill($request->all());
        $skill->account_id = $account->id;
        $skill->update();

        return $skill;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $account = $request->route('account');
        $skill = $request->route('skill');

        Gate::authorize('delete', [Skill::class, $request->route('account')]);

        return $account->skills()->findOrFail($skill->id)->delete();
    }
}
