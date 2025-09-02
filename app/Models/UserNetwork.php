<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNetwork extends Model
{
    protected $table = 'user_networks';

    protected $fillable = [
        'user_id',
        'network_id',
        'type',
    ];

    public const OWNER = 'owner';
    public const CONTRIBUTOR = 'contributor';
    public const CUSTOMER = 'customer';
    public const SUPPLIER = 'supplier';
    public static $TYPES = [
        'owner' => self::OWNER,
        'contributor' => self::CONTRIBUTOR,
        'customer' => self::CUSTOMER,
        'supplier' => self::SUPPLIER
    ];
}
