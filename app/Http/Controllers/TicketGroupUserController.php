<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Network;
use App\Models\TicketGroupUser;
use Illuminate\Http\Request;

class TicketGroupUserController extends Controller
{
    public function index()
    {
        $users = TicketGroupUser::all();

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum usuário encontrado.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Usuários encontrados.',
            'data' => $users,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'ticket_group_id' => 'required|exists:ticket_groups,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $ticketGroupUser = new TicketGroupUser();
        $ticketGroupUser->fill($request->all());
        $ticketGroupUser->save();

        return response()->json([
            'message' => 'Usuário de grupo criado com sucesso.',
            'data' => $ticketGroupUser,
        ], 201);
    }


    public function show(TicketGroupUser $ticketGroupUser)
    {
        return response()->json([
            'message' => 'Usuário de grupo encontrado.',
            'data' => $ticketGroupUser,
        ]);
    }

    public function update(Request $request, TicketGroupUser $ticketGroupUser)
    {
        $ticketGroupUser->fill($request->all());
        $ticketGroupUser->save();

        return response()->json([
            'message' => 'Usuário de grupo atualizado com sucesso.',
            'data' => $ticketGroupUser,
        ]);
    }

    public function destroy(Network $network, Account $account, TicketGroupUser $ticketGroupUser)
    {
        $ticketGroupUser->delete();

        return response()->json([
            'message' => 'Usuário de grupo excluído com sucesso.',
        ], 200);
    }


}
