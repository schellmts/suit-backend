<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenericTableValue extends Model
{
    protected $table = 'generic_tables_values';
    protected $fillable = ['account_id', 'generic_table_id', 'field_id', 'value_field', 'reg_id'];

    public function field()
    {
        return $this->belongsTo(TableField::class, 'field_id');
    }

    public function table()
    {
        return $this->belongsTo(GenericTable::class, 'generic_table_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
