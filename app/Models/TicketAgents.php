<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAgents extends Model
{
    protected $table = 'ticket_agents';

    protected $fillable = ['user_id', 'account_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
