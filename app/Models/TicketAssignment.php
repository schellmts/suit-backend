<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAssignment extends Model
{
    protected $fillable = ['account_id', 'ticket_id', 'assigned_user_id', 'assigned_by', 'created_at', 'updated_at'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
