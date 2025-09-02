<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $table = 'ticket_attachment';

    protected $fillable = [
        'account_id',
        'ticket_id',
        'move_id',
        'filename',
        'url_target',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function move()
    {
        return $this->belongsTo(TicketMovement::class, 'move_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

}
