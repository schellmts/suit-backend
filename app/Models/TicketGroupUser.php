<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketGroupUser extends Model
{
    use HasFactory;

    protected $table = 'ticket_groups_users';

    protected $fillable = [
        'account_id',
        'ticket_group_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketGroup()
    {
        return $this->belongsTo(TicketGroup::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
