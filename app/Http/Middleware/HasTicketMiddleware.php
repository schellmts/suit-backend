<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasTicketMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $hasTicket = $user->accounts->flatMap(function ($account) {
            return $account->subscriptions()->with('items')->get();
        })->contains(function ($subscription) {
            return $subscription->items->contains(function ($item) {
                return $item->stripe_product === 'prod_SWqe3GbkTj9wf4';
            });
        });

        if (!$hasTicket) {
            return response()->json(['message' => 'Você não possui a assinatura do módulo Ticket.'], 403);
        }
        return $next($request);
    }
}
