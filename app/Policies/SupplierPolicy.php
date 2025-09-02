<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupplierPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Account $account): bool
    {
        if (!$user->hasPermission('supplier-view', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Account $account): bool
    {
        if (!$user->hasPermission('supplier-view', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Account $account): bool
    {
        if (!$user->hasPermission('supplier-create', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Account $account): bool
    {
        if (!$user->hasPermission('supplier-edit', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Account $account): bool
    {
        if (!$user->hasPermission('supplier-delete', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Account $account): bool
    {
        if (!$user->hasPermission('supplier-create', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Account $account): bool
    {
        if (!$user->hasPermission('supplier-delete', $account->id) && !$user->isOwner($account->network_id)) {
            abort(403);
        }

        return true;
    }
}
