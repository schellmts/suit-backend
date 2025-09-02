<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    function roles(): BelongsToMany {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    protected $fillable = ['name', 'description'];
}
