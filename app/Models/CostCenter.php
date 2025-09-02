<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CostCenter extends Model
{
    protected $table = 'cost_centers';
    protected $fillable = [
        'name',
        'description',
        'network_id'
    ];

    function groupings(): BelongsToMany {
        return $this->belongsToMany(Grouping::class);
    }

    function network(): BelongsTo {
        return $this->belongsTo(Network::class);
    }
}
