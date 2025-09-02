<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketActions extends Model
{
    use HasFactory;

    protected $table = 'ticket_actions';

    protected $fillable = ['name', 'account_id'];
}
