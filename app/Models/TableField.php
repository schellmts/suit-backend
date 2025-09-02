<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableField extends Model
{
    protected $table = 'table_fields';
    protected $fillable = ['account_id', 'generic_table_id', 'seq_field', 'cod_field', 'description', 'field_type', 'default_value', 'list_field_id', 'label'];

    public function table()
    {
        return $this->belongsTo(GenericTable::class, 'generic_table_id');
    }

    public function values()
    {
        return $this->hasMany(GenericTableValue::class, 'field_id');
    }


    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
