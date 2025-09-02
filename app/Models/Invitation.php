<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $table = 'invitations';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invited_by_user_id',
        'account_id',
        'customer_id',
        'supplier_id',
        'role_id',
        'type',
        'email',
        'token',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by_user_id');
    }

    protected $dates = [
        'expires_at'
    ];

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
