<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\User;

class RedirectTokenController extends Controller
{
    public function generate(Request $request)
    {
        $user = Auth::user();

        $token = Str::random(60);
        Cache::put("redirect_token:$token", $user->id, now()->addMinutes(2));

        return response()->json([
            'redirect_token' => $token,
        ]);
    }

    public function exchange(Request $request)
    {
        $tempToken = $request->input('temp_token');

        if (!$tempToken) {
            return response()->json(['message' => 'Token ausente'], 400);
        }

        $userId = Cache::pull("redirect_token:$tempToken");

        if (!$userId) {
            return response()->json(['message' => 'Token inválido ou expirado'], 401);
        }

        $user = User::with(['networks', 'accounts'])->find($userId);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $token = $user->createToken('App2Redirect')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);

    }
}

