<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->route('account')->subscription('default')->hasProduct(env('ORGANIZATION_STRIPE_ID'))){
            return response()->json(['message' => 'Account without product Organization']);
        }

        return $next($request);
    }
}
