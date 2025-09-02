<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    function groupings() : BelongsToMany {
        return $this->belongsToMany(Grouping::class);
    }

    function account() : BelongsTo {
        return $this->belongsTo(Account::class);
    }

    protected $fillable = ['name', 'description', 'account_id'];
}
