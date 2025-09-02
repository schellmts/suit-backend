<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\RuleEngineService;
use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use App\Models\GenericTable;
use App\Models\Ticket;

class DownloadEmail extends Command
{
    protected $signature = 'email:download {accountId}';
    protected $description = 'Baixa e-mails via IMAP e cria chamados para uma conta específica.';

    public function handle()
    {
        $ruleEngine = app(RuleEngineService::class);
        $accountId = (int) $this->argument('accountId');
        $this->info("Tentando baixar e-mails para a conta ID: {$accountId}");

        try {
            $genericTableData = $this->getImapCredentials($accountId);
            $imapConfig = $this->parseImapConfig($genericTableData);
        } catch (\Exception $e) {
            $this->error("Erro ao obter ou parsear as credenciais IMAP: {$e->getMessage()}");
            return 1;
        }

        try {
            $client = Client::make($imapConfig);
            $client->connect();
        } catch (\Exception $e) {
            $this->error("Falha ao conectar ao servidor IMAP: " . $e->getMessage());
            return 1;
        }

        $this->info("Conectado com sucesso à caixa de entrada de {$imapConfig['username']}");
        $folder = $client->getFolder('INBOX');
        $messages = $folder->messages()
            ->unseen()
            ->limit(10)
            ->setFetchOrder('desc')
            ->get();

        if ($messages->isEmpty()) {
            $this->info("Nenhum e-mail novo encontrado.");
        }

        foreach ($messages as $message) {
            $remetente = $message->getFrom()[0]->mail;
            $assunto = $message->getSubject();
            $corpo = strip_tags($message->getTextBody());

            $user = $this->isRegisteredUserEmail($remetente);

            if (!$user) {
                $this->info("Email de {$remetente} foi ignorado, não é um email registrado.");
                $message->setFlag('Seen');
                $this->line(str_repeat('-', 50));
                continue;
            }

            $account = $user->accounts()->first();
            $ticketData = [
                'title' => $assunto,
                'body' => $corpo,
                'email_abertura_ticket' => $remetente,
                'status' => 1,
                'type' => 1,
                'area_customer' => 'E-mail',
                'category' => 'E-mail',
                'subcategory' => 'E-mail',
                'account_id' => $account->id,
                'created_by' => $user->id,
                'priority' => '6',
                'ticket_origin' => 1
            ];

            $ticket = new Ticket($ticketData);

            $canCreate = false;
            $ruleGroups = $account->ticketRuleGroups()->with(['conditions', 'actions'])->get();

            foreach ($ruleGroups as $ruleGroup) {
                if ($ruleEngine->checkConditions($ruleGroup->conditions, $ticket)) {
                    $canCreate = true;
                    $ruleEngine->executeActions($ruleGroup->actions, $ticket);
                    break;
                }
            }

            if ($canCreate) {
                $ticket->save();
                $this->info("Novo Ticket #{$ticket->id} criado com sucesso para {$remetente}.");
            } else {
                $this->info("Nenhuma regra permitiu a criação do ticket para {$remetente}. Ticket não criado.");
            }

            $message->setFlag('Seen');
            $this->line(str_repeat('-', 50));
        }

        $client->disconnect();
        $this->info('Desconectado. Processo finalizado.');
    }

    protected function isRegisteredUserEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    protected function getImapCredentials(int $accountId): array
    {
        $table = GenericTable::where('table_code', 'parametros_imap_ticket')
            ->where('account_id', $accountId)
            ->with('fields.values')
            ->first();

        if (!$table) {
            throw new \Exception("Nenhuma credencial IMAP encontrada para a conta ID: {$accountId}.");
        }

        return $table->toArray();
    }

    protected function parseImapConfig(array $genericTableData): array
    {
        $config = [];
        foreach ($genericTableData['fields'] as $field) {
            $codField = $field['cod_field'];
            $value = $field['values'][0]['value_field'];

            switch ($codField) {
                case 'imap_host': $config['host'] = $value; break;
                case 'imap_port': $config['port'] = $value; break;
                case 'imap_username': $config['username'] = $value; break;
                case 'imap_password': $config['password'] = $value; break;
                case 'imap_encryption': $config['encryption'] = $value; break;
            }
        }

        $config['validate_cert'] = true;
        $config['protocol'] = 'imap';
        $config['options'] = [];
        $config['folder_check'] = false;

        return $config;
    }
}
