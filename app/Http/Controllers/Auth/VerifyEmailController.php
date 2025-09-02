<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(LoginRequest $request, $id, $hash): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::find($id);

        if (!$user || !hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Usuário não encontrado ou hash inválido.'], 403);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'user' => $user,
                    'message' => 'User verified',
                ]);
            }

            $user->tokens()
                ->where('client_id', env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'))
                ->where('name', 'web')->delete();

            $token = $user->createToken('web')->accessToken;

            if ($user->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }

            $accounts = [];
            foreach ($user->accounts()->get()->load(['subscriptions']) as $account) {
                $account->role = $user->roles()->wherePivot('account_id', $account->id)->get();
                $accounts[] = $account;
            }

            return response()->json([
                'user' => $user,
                'message' => 'User verified',
                'token' => $token,
                'networks' => $user->networks()->get(),
                'accounts' => $accounts,
            ], 200)->withCookie(cookie('web', $token, 60));
        }

        return response()->json(['message' => 'Verification failed'], 400);
    }
}
