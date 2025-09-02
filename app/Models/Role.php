<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    function permissions(): BelongsToMany {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    function userAccount(): HasMany {
        return $this->hasMany(UserAccount::class);
    }

    function account(): BelongsTo {
        return $this->belongsTo(Account::class);
    }

    protected $fillable = ['name', 'description'];
}
