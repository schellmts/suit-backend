<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketOperator extends Model
{
    use HasFactory;

    protected $table = 'ticket_operators';
    protected $fillable = ['symbol'];
}
