<?php

namespace App\Http\Middleware;

use App\Models\UserAccount;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $account = $request->route('account');
        $user = $request->user();

        if (!$user->accounts()->find($account->id)) {
            return response()->json(['message' => 'User not linked to account'], 400);
        }

        $userAccount = UserAccount::where('user_id', $user->id)
            ->where('account_id', $account->id)
            ->first();

        $request->merge(['userAccount' => $userAccount]);

        return $next($request);
    }
}
