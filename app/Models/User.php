<?php

namespace App\Models;

use App\Traits\HasAccountPermissions;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasAccountPermissions;


    // User.php
    public function assignedTickets()
    {
        return $this->hasMany(TicketAssignment::class, 'assigned_user_id');
    }

    public function ticketAgents()
    {
        return $this->hasMany(TicketAgents::class);
    }

    public function ticketGroups()
    {
        return $this->belongsToMany(TicketGroup::class, 'ticket_groups_users')
            ->withTimestamps()
            ->withPivot('account_id');
    }

    public function accountsAsAgent()
    {
        return $this->belongsToMany(Account::class, 'ticket_agents')
            ->withTimestamps();
    }

    function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'user_networks')->withPivot('type');
    }
    public function userAccounts(): HasMany
    {
        return $this->hasMany(UserAccount::class, 'user_id', 'id');
    }
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'user_accounts')->withPivot('role_id');
    }

    function groupings(): BelongsToMany
    {
        return $this->belongsToMany(Grouping::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user_groupings', 'user_grouping_id', 'project_id')
            ->withTimestamps();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_accounts')->withPivot('account_id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'user_customers');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'user_suppliers');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'country_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public const ACTIVE = true;
    public const INACTIVE = false;

    function hasRoleInAccount(Account $account, string $role) : bool {
        return $this->roles()->wherePivot('account_id', $account->id)->get()->contains('name', $role);
    }

    public function isAdmin(Account $account): bool
    {
        return $this->hasRoleInAccount($account, 'owner') || $this->hasRoleInAccount($account, 'host');
    }

    public function isOwnerOfNetwork(Network $network): bool
    {
        $pivot = $this->networks()->find($network->id)?->pivot;
        return $pivot && $pivot->type === 'owner';
    }

    public function isAgentInAccount(Account $account): bool
    {
        return TicketAgents::where('user_id', $this->id)
            ->where('account_id', $account->id)
            ->exists();
    }
}
