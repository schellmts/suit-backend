<?php

namespace App\Http\Middleware;

use App\Models\UserNetwork;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserNetworkMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $network = $request->route('network');
        $user = $request->user();

        if (!$user->networks()->find($network->id)) {
            return response()->json(['message' => 'User not linked to network'], 400);
        }

        $userNetwork = UserNetwork::where('user_id', $user->id)
            ->where('network_id', $network->id)
            ->first();

        $request->merge(['userNetwork' => $userNetwork]);
        $request->merge(['network' => $network]);

        return $next($request);
    }
}
