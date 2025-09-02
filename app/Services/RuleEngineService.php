<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class RuleEngineService
{
    /**
     *
     * @param Ticket $ticket
     * @param Account $account
     * @return void
     */
    public function applyRules(Ticket $ticket, Account $account): void
    {
        info("Iniciando a aplicação de regras para o ticket #{$ticket->id} na conta #{$account->id}.");

        $ruleGroups = $account->ticketRuleGroups()->with(['conditions', 'actions'])
            ->where('account_id', $ticket->account_id)
            ->get();

        info("Total de grupos de regras encontrados: {$ruleGroups->count()}.");

        foreach ($ruleGroups as $ruleGroup) {
            info("Verificando grupo de regras: {$ruleGroup->name} (ID: {$ruleGroup->id}).");

            if ($this->checkConditions($ruleGroup->conditions, $ticket)) {
                info("Condições do grupo de regras '{$ruleGroup->name}' atendidas. Executando ações...");
                $this->executeActions($ruleGroup->actions, $ticket);
                break;
            } else {
                info("Condições do grupo de regras '{$ruleGroup->name}' não atendidas. Passando para a próxima regra...");
            }
        }

        info("Processo de aplicação de regras finalizado.");
    }

    /**
     *
     * @param Collection $conditions
     * @param Ticket $ticket
     * @return bool
     */
    public function checkConditions(Collection $conditions, Ticket $ticket): bool
    {
        if ($conditions->isEmpty()) {
            info("Nenhuma condição encontrada para este grupo de regras. A condição é considerada verdadeira.");
            return true;
        }

        $result = null;

        foreach ($conditions as $condition) {
            $fieldValue = $this->getTicketField($ticket, $condition->condition_type_id);
            $passed = $this->compareValues($fieldValue, $condition->operator_id, $condition->value);

            info("  - Verificando condição: tipo '{$condition->condition_type_id}', operador '{$condition->operator_id}', valor '{$condition->value}' => " . ($passed ? 'PASSOU' : 'FALHOU'));

            if ($condition->logic_operator === 'or') {
                $result = $result === null ? $passed : $result || $passed;
            } else {
                $result = $result === null ? $passed : $result && $passed;
            }
        }

        info("Resultado final das condições: " . ($result ? 'PASSOU' : 'FALHOU'));
        return $result;
    }

    /**
     *
     * @param Collection $actions
     * @param Ticket $ticket
     * @return void
     */
    public function executeActions(Collection $actions, Ticket $ticket): void
    {
        info("Executando ações para o ticket #{$ticket->id}.");

        foreach ($actions as $action) {
            info("  - Executando ação: tipo '{$action->action_type_id}', valor '{$action->action_value}'.");

            switch ($action->action_type_id) {
                case 1: // assign_group
                    $ticket->update(['group_id' => $action->action_value]);
                    info("  - Ação concluída: Ticket #{$ticket->id} atribuído ao grupo #{$action->action_value}.");
                    break;
                case 2: // set_priority
                    $ticket->update(['priority' => $action->action_value]);
                    info("  - Ação concluída: Prioridade do Ticket #{$ticket->id} definida para '{$action->action_value}'.");
                    break;
                default:
                    info("  - Tipo de ação '{$action->action_type_id}' desconhecido. Nenhuma ação executada.");
                    break;
            }
        }
    }

    /**
     *
     * @param Ticket $ticket
     * @param int $conditionTypeId
     * @return mixed
     */
    private function getTicketField(Ticket $ticket, int $conditionTypeId)
    {
        switch ($conditionTypeId) {
            case 2: // email_domain
                return explode('@', $ticket->email_abertura_ticket)[1] ?? null;
            case 3: // ticket_type
                return $ticket->ticket_type;
            default:
                return null;
        }
    }

    /**
     *
     * @param mixed $fieldValue
     * @param int $operatorId
     * @param mixed $conditionValue
     * @return bool
     */
    private function compareValues($fieldValue, int $operatorId, $conditionValue): bool
    {
        switch ($operatorId) {
            case 1: // equals
                return $fieldValue === $conditionValue;
            case 2: // contains
                return str_contains((string) $fieldValue, (string) $conditionValue);
            default:
                return false;
        }
    }
}
