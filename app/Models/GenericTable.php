<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenericTable extends Model
{
    protected $table = 'generic_tables';
    protected $fillable = ['account_id', 'table_code', 'table_desc', 'type_values'];

    public function fields()
    {
        return $this->hasMany(TableField::class, 'generic_table_id');
    }

    public function values()
    {
        return $this->hasMany(GenericTableValue::class, 'generic_table_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
