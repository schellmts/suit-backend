<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListTable extends Model
{
    protected $table = 'list';

    protected $fillable = ['account_id', 'list_code', 'list_name', 'label'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function items()
    {
        return $this->hasMany(ListItemsTable::class, 'list_id');
    }
}
