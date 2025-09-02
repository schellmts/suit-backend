<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Permission;

class AccountObserver
{
    /**
     * Handle the Account "created" event.
     */
    public function created(Account $account): void
    {
        $defaultRoles = [
            'host' => Permission::pluck('name'),
            'maintainer' => Permission::where('name', 'like', '%-view')->where('name', 'like', '%-edit')->pluck('name'),
            'viewer' => Permission::where('name', 'like', '%-view')->pluck('name'),
            'customer' => Permission::where('name', 'like', '%-view')->pluck('name'),
            'supplier' => Permission::where('name', 'like', '%-view')->pluck('name'),
            'contributor' => Permission::where('name', 'like', '%-view')->pluck('name'),
        ];

        foreach ($defaultRoles as $roleName => $permissionSlugs) {
            $role = $account->roles()->create([
                'name' => $roleName,
                'description' => ucfirst($roleName),
            ]);

            $permissions = Permission::whereIn('name', $permissionSlugs)->get();
            $role->permissions()->attach($permissions);
        }
    }

    /**
     * Handle the Account "updated" event.
     */
    public function updated(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "deleted" event.
     */
    public function deleted(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "restored" event.
     */
    public function restored(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "force deleted" event.
     */
    public function forceDeleted(Account $account): void
    {
        //
    }
}
