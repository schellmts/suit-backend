<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthenticatedSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{

    public function __construct(public AuthenticatedSessionService $authSessionService)
    {
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');

        $token = $this->authSessionService->authPersonalAccess($credentials);

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $accounts = [];
        foreach ($user->accounts()->get()->load(['subscriptions']) as $account) {
            $account->role = $user->roles()->wherePivot('account_id', $account->id)->get();
            $accounts[] = $account;
        }

        return response()->json([
            'user' => $user,
            'token' => $token,
            'networks' => $user->networks()->get(),
            'accounts' => $accounts,
        ], 200)->withCookie(cookie('web', $token, 60));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(): JsonResponse
    {
        $this->authSessionService->removePersonalAccess();

        return response()->json(['message' => 'Logout successful']);

         /** @var \App\Models\User $user **/
         $user = Auth::user();
         $user->tokens()
             ->where('client_id', env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'))
             ->where('name', 'web')->delete();
         Cookie::queue(Cookie::forget('web'));

         return response()->json(['message' => 'Logout successful']);
    }
}
