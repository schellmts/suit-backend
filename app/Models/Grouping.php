<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Grouping extends Model
{
    use HasFactory;

    function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'grouping_skills');
    }

    function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    function costCenters(): BelongsToMany {
        return $this->belongsToMany(CostCenter::class);
    }

    protected $fillable = ['name', 'description', 'account_id'];
}
