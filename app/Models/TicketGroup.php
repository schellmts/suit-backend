<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketGroup extends Model
{
    use HasFactory;

    protected $table = 'ticket_groups';

    protected $fillable = ['account_id', 'name', 'description'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'ticket_groups_users')
            ->withTimestamps()
            ->withPivot('account_id')
            ->withPivot('id');
    }
}
