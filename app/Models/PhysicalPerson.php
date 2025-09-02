<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicalPerson extends Model
{
    use HasUuids, SoftDeletes;

    public function account() //assim como nas migratrions que tem a chave estrangeira conecando a tabela account
    {
        return $this->belongsTo(Account::class);//Pessoa juridcia pertence a account
    }
    public function creator() //assim como nas migratrions que tem a chave estrangeira conecando a tabela user
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relacionamento com o usuário que atualizou
     */
    public function updater()//assim como nas migratrions que tem a chave estrangeira conecando a tabela user
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected $table = 'physical_person';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'account_id',
        'country_code',
        'erp_physical_person_code',
        'document_type1',
        'document1',
        'document_type2',
        'document2',
        'passport',
        'birth_date',
        'name',
        'city',
        'neighborhood',
        'street',
        'extra_info1',
        'postal_code',
        'city_code',
        'number',
        'extra_info2',
        'nationality',
        'phone',
        'mobile',
        'email',
        'gender',
        'marital_status',
        'created_by',
        'updated_by',
    ];

}
