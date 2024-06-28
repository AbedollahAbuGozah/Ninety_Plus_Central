<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccountRequest;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function __construct(protected StripeService $stripeService)
    {

    }

    public function store(BankAccountRequest $request)
    {

        $validatedDate = $request->safe()->except(['user_id']);
        $this->stripeService->createStripeAccount(User::find($request->user_id) ,$validatedDate);

    }
}
