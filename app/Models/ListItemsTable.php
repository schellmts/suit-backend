<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListItemsTable extends Model
{
    protected $table = 'list_items';
    protected $fillable = ['account_id', 'item_code', 'item_name', 'list_id'];

    public function list()
    {
        return $this->belongsTo(ListTable::class, 'list_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
