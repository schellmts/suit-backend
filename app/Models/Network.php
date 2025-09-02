<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Network extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_networks');
    }
    function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    function costCenters(): HasMany
    {
        return $this->hasMany(CostCenter::class);
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'user_networks')
            ->wherePivot('type', 'owner')
            ->withTimestamps();
    }

    protected $fillable = ['name', 'description'];

    protected $table = 'networks';
}
