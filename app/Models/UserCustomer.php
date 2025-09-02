<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCustomer extends Model
{
    protected $table = 'user_customers';

    protected $fillable = [
        'user_id',
        'customer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
