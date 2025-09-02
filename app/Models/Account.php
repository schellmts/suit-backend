<?php

namespace App\Models;

use App\Observers\AccountObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;

#[ObservedBy([AccountObserver::class])]
class Account extends Model
{
    use SoftDeletes, HasFactory, Billable;

    // TODO # Avaliar necessidade do relacionamento
    // public function userAccounts(): HasMany
    // {
    //     return $this->hasMany(UserAccount::class);
    // }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_accounts');
        // TODO # Avaliar necessidade das especificações
        // ->using(UserAccount::class)
        // ->withPivot(['role_id', 'add_by_user_id', 'removed_by_user_id'])
        // ->withTimestamps();
    }

    public function ticketAgents()
    {
        return $this->hasMany(TicketAgents::class);
    }

    public function ticketGroups()
    {
        return $this->hasMany(TicketGroup::class);
    }

    public function ticketGroupUsers()
    {
        return $this->hasMany(TicketGroupUser::class);
    }

    public function agentUsers()
    {
        return $this->belongsToMany(User::class, 'ticket_agents')
            ->withTimestamps();
    }

    public function values()
    {
        return $this->hasMany(GenericTableValue::class);
    }
    public function tables()
    {
        return $this->hasMany(GenericTable::class);
    }

    public function lists()
    {
        return $this->hasMany(ListTable::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function owners()
    {
        $network = Network::find($this->network_id);
        return $network->owners();
    }

    function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    function groupings(): HasMany
    {
        return $this->hasMany(Grouping::class);
    }

    function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function ticketRuleGroups()
    {
        return $this->hasMany(TicketRuleGroup::class);
    }

    public function ticketActions()
    {
        return $this->hasMany(TicketRuleAction::class);
    }

    function physicalPersons(): HasMany
    {
        return $this->hasMany(PhysicalPerson::class);
    }

    function juridicalPersons(): HasMany
    {
        return $this->hasMany(JuridicalPerson::class);
    }
    protected $fillable = ['name', 'description', 'status', 'active', 'network_id'];

    public const AUTHORIZED = 'authorized';
    public const UNAUTHORIZED = 'unauthorized';
    public const BLOCKED = 'blocked';
    public const UNBLOCKED = 'unblocked';
    public const ACTIVE = true;
    public const INACTIVE = false;
    public static $TYPES = [
        'authorized' => self::AUTHORIZED,
        'unauthorized' => self::UNAUTHORIZED,
        'blocked' => self::BLOCKED,
        'unblocked' => self::UNBLOCKED
    ];
}
