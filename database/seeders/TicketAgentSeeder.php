<?php

namespace Database\Seeders;

use App\Models\TicketAgents;
use App\Models\UserAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pega todos os usuários com role 'contributor'
        $contributors = UserAccount::whereHas('role', function ($query) {
            $query->where('name', 'contributor');
        })->get();

        foreach ($contributors as $userAccount) {
            // Verifica se o userAccount tem a relação de user_id e account_id
            if ($userAccount->user && $userAccount->account) {
                // Agora, associar o usuário com o account_id na tabela ticket_agents
                TicketAgents::firstOrCreate([
                    'user_id' => $userAccount->user_id,
                    'account_id' => $userAccount->account_id,
                ]);
            }
        }
    }
}
