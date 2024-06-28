<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\Token;
use Stripe\Transfer;

class StripeService
{
    protected $provider;
    private $invoiceData;

    public function __construct(protected InvoiceService $invoiceService)
    {
        Stripe::setApiKey(config('stripe.secret_key'));
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

    public function captureOrder($sessionId, $purchasable)
    {
        $session = $this->provider->checkout->sessions->retrieve($sessionId, [
            'expand' => ['payment_intent'],
        ]);

        if ($session && $session->payment_status === 'paid') {
            logger(__METHOD__);
            logger(__LINE__);

            $this->updateInvoiceData($session, $purchasable, $session->client_reference_id);
        }

        return $session;
    }

    private function setInvoiceData($customer, $purchasable, $currency)
    {
        $invoiceData = [
            'date' => now()->toDateString(),
            'due_date' => now()->addDay()->toDateString(),
            'amount' => $purchasable->price,
            'currency' => $currency,
            'tax' => 0,
            'payment_method' => '',
            'notes' => 'Thank you for your purchase!',
            'payment_status' => 'unpaid',
            'paid_date' => '',
            'user_id' => $customer->id,
            'description' => '',
            'quantity' => 1,
            'invoiceable_type' => get_class($purchasable),
            'invoiceable_id' => $purchasable->id
        ];

        $this->invoiceService->create($invoiceData, new Invoice());
    }

    private function updateInvoiceData($session, $purchasable, $payerId)
    {
        $purchasable->invoices()->where([
            'user_id' => $payerId,
        ])->update([
            'payment_status' => 'paid',
            'paid_date' => now()->toDateString(),
            'payment_method' => $session->payment_method_types[0],
        ]);
    }

    public function createStripeAccount($user, $data)
    {
        $account = Account::create([
            'type' => 'custom',
            'country' => $data['country'] ?? 'US',
            'email' => $user->email,
            'capabilities' => [
                'transfers' => ['requested' => true],
            ],
        ]);


        $token = Token::create([
            'bank_account' => [
                'country' => $data['country'] ?? 'US',
                'currency' => config('stripe.currency'),
                'account_holder_name' => $data['account_holder_name'],
                'account_holder_type' => $data['account_holder_type'] ?? 'individual',
                'routing_number' => $data['routing_number'],
                'account_number' => $data['account_number'],
            ],
        ]);


        BankAccount::create([
            'stripe_bank_account_id' => $token->id,
            'account_holder_name' => $data['account_holder_name'],
            'account_holder_type' => $data['account_holder_type'],
            'bank_name' => $data['banck_name'],
            'last4' => substr($data['account_number'], -4),
        ]);

        $account->external_accounts->create(['external_account' => $token->id]);
    }

    public function transfareMoey($user, $amount)
    {
        $bankAccount = $user->bankAccounts()->first();


        $transfer = Transfer::create([
            'amount' => $amount,
            'currency' => config('stripe.currency'),
            'destination' => $bankAccount->stripe_bank_account_id,
        ]);

        return $transfer;
    }

}
