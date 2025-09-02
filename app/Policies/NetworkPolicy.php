<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Network;
use App\Models\User;
use App\Models\UserNetwork;
use Illuminate\Auth\Access\Response;

class NetworkPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Network $network): bool
    {
        if (!$user->isOwner($network->id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Network $network): bool
    {
        if (!$user->isOwner($network->id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Network $network): bool
    {
        if (!$user->isOwner($network->id)) {
            abort(403);
        }

        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Network $network): bool
    {
        if (!$user->isOwner($network->id)) {
            abort(403);
        }

        return true;
    }
}
