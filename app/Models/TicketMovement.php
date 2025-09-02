<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMovement extends Model
{
    protected $table = 'ticket_movement';

    protected $fillable = [
        'account_id',
        'ticket_id',
        'body',
        'email_cc',
        'privacity',
        'status',
        'type',
        'user_id',
        'origin',
        'created_by'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    // TicketMovement.php
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class, 'move_id');
    }


    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
