<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCondition extends Model
{
    use HasFactory;

    protected $table = 'ticket_conditions';

    protected $fillable = ['name', 'account_id'];
}
