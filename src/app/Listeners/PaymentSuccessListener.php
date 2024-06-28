<?php

namespace App\Listeners;

use App\Events\PaymentSuccess;
use App\Facades\NinetyPlusCentralFacade;
use App\Mail\InvoiceMail;
use App\Notifications\SendPaymentSuccessNotification;
use App\services\NinetyPlusCentral;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Action;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PaymentSuccessListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentSuccess $event): void
    {
        $user = $event->user->resolveUser();
        $purchasable = $event->purchasable;
        $relation = NinetyPlusCentralFacade::generateRelationName($purchasable, 1);

        $user->{$relation}()->attach($purchasable->id);

        $user->notify(new SendPaymentSuccessNotification($purchasable, ucfirst(class_basename($purchasable))));
        $this->callPostPurchaseHook($purchasable, $user);
    }

    private function callPostPurchaseHook($purchasable, $customer)
    {
        $methodName = 'postPurchase' . ucfirst(class_basename($purchasable));
        if (method_exists(NinetyPlusCentral::class, $methodName)) {
            NinetyPlusCentralFacade::{$methodName}($purchasable, $customer);
        }
    }


}
