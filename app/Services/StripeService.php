<?php
namespace App\Services;

use App\Models\Account;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookHandled;

class StripeService
{

    function specificationsUpdate(WebhookHandled $event)
    {
        if ($account = Cashier::findBillable($event->payload['data']['object']['customer'])) {
            $data = $event->payload['data']['object'];

            if ($subscription = $account->subscriptions()->where(['stripe_id' => $data['id']])->get()[0]) {
                foreach ($data['items']['data'] as $item) {
                    $subscription->items()->updateOrCreate([
                        'stripe_id' => $item['id'],
                    ], [
                        'specifications' => !empty($item['price']['metadata']) ? json_encode($item['price']['metadata']) : null,
                    ]);
                }
            }
        }
    }

    function getCheckout(Account $account, array $prices): string
    {
        $result = $account->checkout($prices, [
            // TODO Verificar se vai precisar de tax_id_collection
            //'tax_id_collection' => ['enabled' => true],
            'mode' => 'subscription',
            'billing_address_collection' => 'required',
            'phone_number_collection' => ['enabled' => true],
            'success_url' => env('APP_URL_FRONT') . '/authentication/login/',
            'cancel_url' => env('APP_URL_FRONT') . '/authentication/login/'
        ]);

        return $result->url;
    }

    function getLicensePrice()
    {
        $stripe = Cashier::stripe();
        return $stripe->products->retrieve(env('LICENSE_STRIPE_ID'))->default_price;
    }
}
