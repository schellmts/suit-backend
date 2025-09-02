<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketRuleGroup extends Model
{
    use HasFactory;

    protected $table = 'ticket_rule_groups';

    protected $fillable = [
        'account_id',
        'name',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function conditions()
    {
        return $this->hasMany(TicketRuleCondition::class, 'rule_group_id');
    }

    public function actions()
    {
        return $this->hasMany(TicketRuleAction::class, 'rule_group_id');
    }

    public function ruleGroup()
    {
        return $this->belongsTo(TicketRuleGroup::class, 'rule_group_id');
    }

    public function conditionType()
    {
        return $this->belongsTo(TicketCondition::class);
    }

    public function operator()
    {
        return $this->belongsTo(TicketOperator::class);
    }
}
