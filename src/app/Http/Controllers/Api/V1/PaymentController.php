<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\PaymentSuccess;
use App\Facades\NinetyPlusCentralFacade;
use App\Http\Controllers\Api\V1\BaseController;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maize\Markable\Mark;
use Maize\Markable\Models\Favorite;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class PaymentController extends BaseController
{
    public function __construct(protected StripeService $stripeService)
    {
        $this->middleware('auth:api', ['only' => ['checkout']]);
    }

    public function checkout(Request $request, $purchasableType, $purchasableId)
    {
        if (!($purchasableModelClass = NinetyPlusCentralFacade::getPurchasableModel($purchasableType))) {
            throw new RouteNotFoundException();
        }

        $purchasable = $purchasableModelClass::findOrFail($purchasableId);

        $session = $this->stripeService->createOrder(
            $purchasable->price * 100,
            config('stripe.currency'),
            route('payment.status', ['purchasableType' => $purchasableModelClass, 'purchasableId' => $purchasableId]),
            route('payment.cancel'));

        if (isset($session['id']) && $session['id'] != null) {
            return $this->success(['approval_url' => $session->url], 'Payment Success', 200);
        } else {
            return $this->error($order['message'] ?? 'Something went wrong.', 400);
        }

    }

    public function status(Request $request)
    {
        $session = $this->stripeService->captureOrder($request->query('session_id'));

        if ($session && $session->payment_status == 'paid') {
            $clientUser = User::find($session->client_reference_id);
            PaymentSuccess::dispatch($clientUser, $request->query('purchasableType'), $request->query('purchasableId'), $session);
            return $this->success([], 'Payment success', 200);
        } else {
            return $this->error('Payment failed', 400);
        }
    }

    public function cancel($request)
    {
        return $this->success([], 'You have canceled the transaction.', 200);

    }
}
