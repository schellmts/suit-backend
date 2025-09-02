<?php

namespace App\Http\Middleware;

use App\Services\AuthenticatedSessionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class UserVerified
{
    public function __construct(public AuthenticatedSessionService $authSessionService)
    {

    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->hasVerifiedEmail()) {
            $this->authSessionService->removePersonalAccess();

            abort(403, "Email not verified");
        }

        return $next($request);
    }
}
