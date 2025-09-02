<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectUserGroupingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $account = $request->route('account');
        $project = $request->route('project');

        Gate::authorize('create', ['project-user-grouping', $account]);

        $project = $account->projects()->findOrFail($project->id);

        return $project->users()->attach($request->user_grouping_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $account = $request->route('account');
        $project = $request->route('project');

        Gate::authorize('view', ['project-user-grouping', $account]);

        return $account->projects()->findOrFail($project->id)->load('user_groupings');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $account = $request->route('account');
        $project = $request->route('project');

        Gate::authorize('delete', ['project-user-grouping', $account]);

        if($account->projects()->findOrFail($project->id)){
            return response()->json($project->users()->detach([$request->user_grouping_id]));
        }else{
            return response()->json(["message" => "Project not linked to account"], 400);
        }
    }
}
