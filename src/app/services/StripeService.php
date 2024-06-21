<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\StripeClient;

class StripeService
{
    protected $provider;
    private $invoiceData;

    public function __construct()
    {
        $this->provider = new StripeClient(config('stripe.secret_key'));
    }

    public function createOrder($purchasable, $currency, $returnUrl, $cancelUrl, $user = null)
    {
        $customer = $user ?? auth()->user();
        $this->setInvoiceData($customer, $purchasable, $currency);

        $session = $this->provider->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $purchasable->title,
                    ],
                    'unit_amount' => $purchasable->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => config('stripe.mode'),
            'success_url' => $returnUrl . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'client_reference_id' => $user?->id ?? $customer->id,
            'metadata' => [
                'invoice_data' => json_encode($this->invoiceData),
            ],
        ]);


        Log::info('PayPal Create Order Response', ['response' => $session]);

        return $session;
    }

    public function captureOrder($sessionId)
    {
        $session = $this->provider->checkout->sessions->retrieve($sessionId, [
            'expand' => ['payment_intent'],
        ]);

        if ($session && $session->payment_status === 'paid') {
            logger('wow');
           $session = $this->updateInvoiceData($session);
        }
        logger('afert_session');
        logger($session);
        return $session;
    }

    private function setInvoiceData($customer, $purchasable, $currency)
    {
        $this->invoiceData =$invoiceData = [
            'invoice_id' => uniqid('inv_'),
            'date' => now()->toDateString(),
            'due_date' => now()->addDay()->toDateString(),
            'customer' => [
                'name' => $customer->full_name,
                'email' => $customer->email,
                'address' => $customer->address ?? '',
            ],
            'items' => [
                [
                    'description' => 'Course Purchase',
                    'quantity' => 1,
                    'unit_price' => $purchasable->price,
                    'total_price' => $purchasable->price,
                ],
            ],
            'total_amount' => $purchasable->price,
            'currency' => $currency,
            'tax' => 0,
            'payment_method' => '',
            'notes' => 'Thank you for your purchase!',
            'payment_status' => 'unpaid',
            'paid_date' => '',
        ];
    }

    private function updateInvoiceData($session)
    {
        $invoiceData = json_decode($session->metadata['invoice_data'], true);

        $invoiceData['payment_status'] = 'paid';
        $invoiceData['paid_date'] = now()->toDateString();
        $invoiceData['payment_method'] = $session->payment_method_types[0];

        $session->metadata['invoice_data'] = json_encode($invoiceData);

        return $session;
    }
}
