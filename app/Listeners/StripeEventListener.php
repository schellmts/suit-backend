<?php

namespace App\Listeners;

use App\Services\StripeService;
use Laravel\Cashier\Events\WebhookHandled;

class StripeEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct(public StripeService $stripeService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookHandled $event): void
    {
        ds($event);
        $type = $event->payload['type'];

        switch ($type){
            case 'customer.subscription.created':
                $this->stripeService->specificationsUpdate($event);

            case 'customer.subscription.updated':
                $this->stripeService->specificationsUpdate($event);

            default:
                break;
        }
    }
}
