<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketRuleAction extends Model
{
    use HasFactory;

    protected $table = 'ticket_rule_actions';

    protected $fillable = [
        'rule_group_id',
        'action_type_id',
        'action_value',
        'account_id'
    ];

    public function ruleGroup()
    {
        return $this->belongsTo(TicketRuleGroup::class, 'rule_group_id');
    }

    public function actionType()
    {
        return $this->belongsTo(TicketAction::class, 'action_type_id');
    }
}
