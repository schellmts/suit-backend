<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCheckoutRequest;
use App\Models\Account;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use Stripe\PaymentLink;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function __construct(public StripeService $stripeService)
    {
        //
    }
    /**
     * Retrieves active products and prices.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getProducts(): JsonResponse
    {
        $stripe = Cashier::stripe();

        $products = [];
        foreach ($stripe->products->search(['query' => 'active: \'true\'']) as $product) {
            $product->prices = $stripe->prices->search(['query' => 'product: \'' . $product['id'] . '\' AND active: \'true\'']);
            $products[] = $product;
        }

        return response()->json($products);
    }
    public function getUserSubscriptions(Request $request)
    {
        $user = Auth::user();

        $subscriptions = $user->accounts->flatMap(function ($account) {
            return $account->subscriptions()
                ->with('items')
                ->get()
                ->map(function ($subscription) use ($account) {
                    return [
                        'id' => $subscription->id,
                        'account_id' => $account->id,
                        'stripe_id' => $subscription->stripe_id,
                        'stripe_status' => $subscription->stripe_status,
                        'created_at' => $subscription->created_at,
                        'updated_at' => $subscription->updated_at,
                        'items' => $subscription->items->map(function ($item) {
                            $stripe = Cashier::stripe();
                            $product = $stripe->products->retrieve($item->stripe_product);

                            return [
                                'id' => $item->id,
                                'subscription_id' => $item->subscription_id,
                                'stripe_id' => $item->stripe_id,
                                'stripe_product' => $product->id,
                                'product_name' => $product->name,
                                'product_description' => $product->description,
                                'stripe_price' => $item->stripe_price,
                                'quantity' => $item->quantity,
                                'specifications' => json_decode($item->specifications, true),
                                'created_at' => $item->created_at,
                                'updated_at' => $item->updated_at,
                            ];
                        }),
                    ];
                });
        });

        return response()->json([
            'subscriptions' => $subscriptions->values(),
        ]);
    }


    /**
     * Retrieves link checkout of products and prices.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getCheckout(GetCheckoutRequest $request)
    {
        $prices = $request['prices'];
        $prices[] = [
            'price' => $this->stripeService->getLicensePrice(),
            'quantity' => 1,
            'adjustable_quantity' => [
                'enabled' => true,
                'minimum' => 1,
            ]
        ];

        return response()->json(['checkout_url' => $this->stripeService->getCheckout($request->route('account'), $prices)]);
    }

}
