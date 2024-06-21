<?php

namespace App\Listeners;

use App\Events\PaymentSuccess;
use App\Facades\NinetyPlusCentralFacade;
use App\Mail\InvoiceMail;
use App\Notifications\SendPaymentInvoiceNotification;
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
        $purchasableModelClass = $event->purchasableType;
        $purchasableId = $event->purchasableId;
        $purchasable = $purchasableModelClass::find($purchasableId);
        $relation = NinetyPlusCentralFacade::generateRelationName($purchasable, 1);
        logger($relation);
        $user->{$relation}()->attach($purchasableId, [
            'properties' => json_encode($event->paymentSession),
        ]);
        $this->callPostPurchaseHook($purchasableModelClass, $purchasable);

//        Mail::to($user->email)->sned(new InvoiceMail($event->invoiceData));
    }

    private function callPostPurchaseHook($purchasableModelClass, $purchasable)
    {
        $methodName = 'postPurchase' . ucfirst(class_basename($purchasableModelClass));
        logger($methodName);
        if (method_exists(NinetyPlusCentral::class, $methodName)) {
            NinetyPlusCentralFacade::{$methodName}($purchasable);
        }
    }


}
