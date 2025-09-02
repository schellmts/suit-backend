<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasUuids, SoftDeletes;
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_customers');
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    protected $table = 'customers';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'account_id',
        'type',
        'internal',
        'name',
        'document_number',
        'document_type',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'postal_code',
        'address_line',
        'timezone',
        'preferred_language',
        'metadata',
    ];

    public const INDIVIDUAL = 'individual';
    public const BUSINESS = 'business';

    public static $TYPES = [
        'individual' => self::INDIVIDUAL,
        'business' => self::BUSINESS,
    ];
}
