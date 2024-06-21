<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\StripeClient;

class StripeService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new StripeClient(config('stripe.secret_key'));
    }

    public function createOrder($amount, $currency, $returnUrl, $cancelUrl, $userId = null)
    {
        $session = $this->provider->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => 'Course',
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => config('stripe.mode'),
            'success_url' => $returnUrl . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'client_reference_id' => $userId ?? auth()->id(),
        ]);


        Log::info('PayPal Create Order Response', ['response' => $session]);

        return $session;
    }

    public function captureOrder($sessionId)
    {
        $session = $this->provider->checkout->sessions->retrieve($sessionId);
        return $session;

    }
}
