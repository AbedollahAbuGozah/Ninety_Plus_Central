<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Resources\InvoiceResource;
use App\Models\User;
use App\services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends BaseController
{
    public function __construct(protected InvoiceService $invoiceService)
    {

    }

    public function index(Request $request, User $user)
    {
        $invoices = $user->resolveUser()->invoices();
        return $this->success(InvoiceResource::collection($invoices, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }

}
