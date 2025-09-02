<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;

class GroupingSkillPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Account $account)
    {
        if (!$user->hasPermission('grouping-view', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Account $account): bool
    {
        if (!$user->hasPermission('grouping-create', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Account $account): bool
    {
        if (!$user->hasPermission('grouping-delete', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }
}
