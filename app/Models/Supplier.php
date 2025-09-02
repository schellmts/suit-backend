<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasUuids, SoftDeletes;
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_suppliers');
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    protected $table = 'suppliers';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'account_id',
        'type',
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
