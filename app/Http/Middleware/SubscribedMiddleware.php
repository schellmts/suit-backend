<?php

namespace App\Http\Middleware;

use App\Models\Account;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->route('account')?->subscribed()){
            return response()->json(['message' => 'Account without subscription']);
        }

        if(!$request->route('account')?->subscription('default')->valid()){
            return response()->json(['message' => 'Account without valid subscription']);
        }

        return $next($request);
    }
}
