<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketGroupStoreRequest;
use App\Models\Account;
use App\Models\Network;
use App\Models\TicketGroup;
use Illuminate\Http\Request;

class TicketGroupController extends Controller
{
    public function index(Network $network, Account $account)
    {
        // AQUI: Usamos eager loading para carregar os usuários de cada grupo.
        $groups = $account->ticketGroups()->with('users')->get();

        if ($groups->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum grupo de atendimento encontrado para esta conta.',
                'data' => [],
            ], 200);
        }

        // A resposta JSON agora incluirá um array 'users' dentro de cada objeto de grupo.
        return response()->json([
            'message' => 'Grupos de atendimentos encontrados.',
            'data' => $groups,
        ], 200);
    }

    public function store(TicketGroupStoreRequest $request)
    {
        $grouping = new TicketGroup();
        $grouping->fill($request->all());
        $grouping->account_id = $request->route('account')->id;
        $grouping->save();

        return response()->json([
            'message' => 'Grupo de atendimento criado com sucesso.',
            'data' => $grouping,
        ], 201);
    }

    public function show(Network $network, Account $account, TicketGroup $ticketGroup)
    {
        return response()->json([
            'message' => 'Grupo de atendimento encontrado.',
            'data' => $ticketGroup,
        ]);
    }

    public function update(Request $request, Network $network, Account $account, TicketGroup $ticketGroup)
    {
        $ticketGroup->fill($request->all());
        $ticketGroup->save();

        return response()->json([
            'message' => 'Grupo de atendimento atualizado com sucesso.',
            'data' => $ticketGroup,
        ]);
    }

    public function destroy(Network $network, Account $account, TicketGroup $ticketGroup)
    {
        $ticketGroup->delete();

        return response()->json([
            'message' => 'Grupo de atendimento deletado com sucesso.',
        ]);
    }
}
