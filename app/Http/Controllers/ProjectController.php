<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $account = $request->route('account');
        Gate::authorize('viewAny', [Project::class, $account]);

        if($request->user()->hasPermission('project-viewAny', $account->id)){
            return $account->projects()->get();
        }

        return $request->user()->projects()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectStoreRequest $request)
    {
        $account = $request->route('account');
        Gate::authorize('create', [Project::class, $account]);

        $project = new Project();
        $project->fill($request->all());
        $project->account_id = $account->id;

        if(!$account->users()->find($project->user_manager)){
            return response()->json(['message' => 'This user manager not linked to this account'], 400);
        }

        if(!$account->customers()->find($project->customer_id)){
            return response()->json(['message' => 'Customer not linked to this account'], 400);
        }

        if($project->user_customer_approver){
            $customer = Customer::find($project->customer_id);
            if(!$customer->users()->firstOrfail('user_id', $project->user_customer_approver)){
                return response()->json(['message' => 'This user can not approve for this customer'], 400);
            }
        }
        $project->content = json_encode($project->content);

        $project->save();

        return $project;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $account = $request->route('account');
        Gate::authorize('view', [Project::class, $account]);

        if(!$request->user()->projects()->firstOrFail('project_id', $request->route('project')->id) && !$request->user()->isOwner($account->network_id)){
            return response()->json(['message' => 'Project not found'], 404);
        }

        return $request->user()->projects()->firstOrFail('project_id', $request->route('project')->id)->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $account = $request->route('account');
        Gate::authorize('update', [Project::class, $account]);
        dd($request->user()->projects()->get());
        if(!$request->user()->projects()->firstOrFail('project_id', $request->route('project')->id) && !$request->user()->isOwner($account->network_id)){
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project = Project::findOrFail($request->route('project')->id);
        $project->fill($request->only(Project::$allowed));
        $project->account_id = $account->id;

        if(!$account->users()->find($project->user_manager)){
            return response()->json(['message' => 'This user manager not linked to this account'], 400);
        }

        if($project->user_customer_approver){
            $customer = Customer::find($project->customer_id);
            if(!$customer->users()->firstOrfail('user_id', $project->user_customer_approver)){
                return response()->json(['message' => 'This user can not approve for this customer'], 400);
            }
        }
        $project->content = json_encode($project->content);

        $project->update();

        return $project;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $account = $request->route('account');
        $project = $request->route('project');

        Gate::authorize('delete', [Project::class, $account, $project]);

        if(!$request->user()->projects()->firstOrFail('project_id', $request->route('project')->id) && !$request->user()->isOwner($account->network_id)){
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();
    }
}
