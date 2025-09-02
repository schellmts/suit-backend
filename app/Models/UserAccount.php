<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccount extends Model
{
    private $user;
    private $account;
    private $role;

    public function __construct() {
        $this->user = new User();
        $this->account = new Account();
        $this->role = new Role();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_accounts';

    protected $fillable = ['user_id', 'account_id', 'role_id', 'add_by_user_id', 'removed_by_user_id'];


}
