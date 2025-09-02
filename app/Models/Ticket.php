<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'ticket';

    protected $fillable = [
        'account_id', 'project_id', 'customer_id', 'title', 'body',
        'status', 'type', 'area_customer', 'category', 'subcategory',
        'tags', 'priority', 'assigned_area', 'accuracy_resolution',
        'satisfaction_level', 'obs_evaluation', 'user_ticket_id', 'agent_id', 'email_cc',
        'date_exp_first_interaction', 'date_last_interaction', 'date_exp_finish',
        'date_open', 'date_finished', 'date_accept_customer', 'group_id', 'ticket_origin',
        'email_abertura_ticket', 'ticket_budgeted_value', 'ticket_hours_aprov',
        'ticket_hours_work', 'ticket_hours_lim', 'reserved_1', 'reserved_2', 'reserved_3',
        'reserved_4', 'reserved_5', 'created_by', 'updated_prog', 'created_prog',
        'related_ticket_id',
    ];

    public function movements()
    {
        return $this->hasMany(TicketMovement::class, 'ticket_id');
    }
    public function ticketAgents()
    {
        return $this->belongsToMany(TicketAgents::class, '');
    }


    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class, 'ticket_id');
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class, 'ticket_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function ticketAgent()
    {
        return $this->hasOne(TicketAgents::class, 'user_id', 'agent_id');
    }


    public function ticketGroup()
    {
        return $this->belongsTo(TicketGroup::class, 'group_id');
    }



}
