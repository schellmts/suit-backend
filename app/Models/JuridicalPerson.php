<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // substitua HasUlids
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JuridicalPerson extends Model
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

    protected $table = 'juridical_person';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'account_id',
        'country_code',
        'document_type1',
        'document1',
        'document_type2',
        'document2',
        'document_type3',
        'document3',
        'company_opening_date',
        'city',
        'neighborhood',
        'street',
        'postal_code',
        'nationality',
        'phone',
        'mobile',
        'email',
        'business_area',
        'corporate_name',
        'trade_name',
        'company_type',
        'number',
        'complement',
        'status',
        'created_by',
        'updated_by'
    ];
}
