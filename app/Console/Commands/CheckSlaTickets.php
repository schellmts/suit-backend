<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckSlaTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:check-sla';

    protected $description = 'Verifica e atualiza o status de tickets com SLA vencido.';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $ticketsFirstInteractionExpired = Ticket::where('date_exp_first_interaction', '<', $now)
            ->where('status', '1')
            ->get();

        foreach ($ticketsFirstInteractionExpired as $ticket) {
            $ticket->status = '7';
            $ticket->save();
            $this->info("Ticket #{$ticket->id} teve o SLA de 1ª interação vencido e foi atualizado para status 7.");
        }

        $ticketsFinishExpired = Ticket::where('date_exp_finish', '<', $now)
            ->whereNotIn('status', ['4', '5', '6', '7'])
            ->get();

        foreach ($ticketsFinishExpired as $ticket) {
            $ticket->status = '8';
            $ticket->save();
            $this->info("Ticket #{$ticket->id} teve o SLA de finalização vencido e foi atualizado para status 8.");
        }

        $this->info("Verificação de SLA concluída.");
    }
}
