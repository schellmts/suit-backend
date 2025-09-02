<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupingSkillRequest;
use App\Models\Account;
use App\Models\Grouping;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GroupingSkillController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupingSkillRequest $request)
    {
        $account = $request->route('account');
        $grouping = $request->route('grouping');

        Gate::authorize('create', ['grouping-skill', $account]);

        $grouping = $account->groupings()->findOrFail($grouping->id);

        return $grouping->skills()->attach($request->skill_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $account = $request->route('account');
        $grouping = $request->route('grouping');

        Gate::authorize('view', ['grouping-skill', $account]);

        return $account->groupings()->findOrFail($grouping->id)->load('skills');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $account = $request->route('account');
        $grouping = $request->route('grouping');

        Gate::authorize('delete', ['grouping-skill', $account]);

        if($account->groupings()->findOrFail($grouping->id)){
            return response()->json($grouping->skills()->detach([$request->skill_id]));
        }else{
            return response()->json(["message" => "Grouping not linked to account"], 400);
        }
    }
}
