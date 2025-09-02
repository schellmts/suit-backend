<?php

namespace App\Traits;

use App\Models\Network;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

trait HasAccountPermissions
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_accounts')
                    ->withPivot('account_id')
                    ->withTimestamps();
    }

    function networks()
    {
        return $this->belongsToMany(Network::class, 'user_networks')->withPivot('type');
    }

    public function hasPermission(string $permission, string|int $accountId): bool
    {
        return $this->roles()
            ->wherePivot('account_id', $accountId)
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })->exists();
    }

    public function isOwner(string $networkId) {
        return $this->networks()
        ->wherePivot('network_id', $networkId)
        ->wherePivot('type', 'owner')
        ->exists();
    }

    public function getPermissionsForAccount(string|int $accountId): Collection
    {
        return $this->roles()
            ->wherePivot('account_id', $accountId)
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique();
    }
}
