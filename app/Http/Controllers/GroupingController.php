<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupingStoreRequest;
use App\Models\Account;
use App\Models\Grouping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GroupingController extends Controller
{
    /**
     * Display a listing of the grouping.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', [Grouping::class, $request->route('account')]);

        return response()->json($request->route('account')->groupings()->get());
    }

    /**
     * Store a newly created grouping in storage.
     */
    public function store(GroupingStoreRequest $request)
    {
        Gate::authorize('create', [Grouping::class, $request->route('account')]);

        $grouping = new Grouping();
        $grouping->fill($request->all());
        $grouping->account_id = $request->route('account')->id;
        $grouping->save();

        return $grouping;
    }

    /**
     * Display the specified grouping.
     */
    public function show(Request $request)
    {
        Gate::authorize('view', [Grouping::class, $request->route('account')]);

        return $request->route('account')->groupings()->findOrFail($request->route('grouping')->id);
    }

    /**
     * Update the specified grouping in storage.
     */
    public function update(Request $request)
    {
        Gate::authorize('update', [Grouping::class, $request->route('account')]);
        $grouping = $request->route('grouping');
        $grouping->fill($request->all());
        $grouping->account_id = $request->route('account')->id;
        $grouping->update();

        return $grouping;
    }

    /**
     * Remove the specified grouping from storage.
     */
    public function destroy(Request $request)
    {
        Gate::authorize('delete', [Grouping::class, $request->route('account')]);

        return $request->route('account')->groupings()->findOrFail($request->route('grouping')->id)->delete();
    }
}
