<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketRuleCondition extends Model
{
    use HasFactory;

    protected $table = 'ticket_rule_conditions';

    protected $fillable = [
        'rule_group_id',
        'condition_type_id',
        'operator_id',
        'value',
        'logic_operator',
        'condition_group',
        'account_id'
    ];

    public function ruleGroup()
    {
        return $this->belongsTo(TicketRuleGroup::class, 'rule_group_id');
    }

    public function conditionType()
    {
        return $this->belongsTo(TicketCondition::class, 'condition_type_id');
    }

    public function operator()
    {
        return $this->belongsTo(TicketOperator::class, 'operator_id');
    }
}
