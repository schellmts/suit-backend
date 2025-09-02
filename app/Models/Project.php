<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes, HasUuids;

    function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_manager');
    }

    function customerApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_customer_approver');
    }

    function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user_groupings','project_id', 'user_grouping_id')
            ->withTimestamps();
    }

    public static array $allowed = ['user_manager', 'user_customer_approver', 'name', 'description', 'content', 'status', 'manual', 'with_warranty', 'warranty_date'];

    protected $fillable = ['account_id',  'user_manager', 'customer_id', 'user_customer_approver', 'name', 'description', 'content', 'status', 'manual', 'with_warranty', 'warranty_date'];
}
