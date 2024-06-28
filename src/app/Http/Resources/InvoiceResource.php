<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class InvoiceResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'user' => $this->user()->select('id', 'first_name')->first(),
            'invoiceable' => $this->invoiceable()->select(['id', 'title as name'])->get(),
            'type' => ucfirst(class_basename($this->invoiceable_type)),
            'date' => $this->date,
            'due_date' => $this->when($this->payment_status == 'unpaid', fn() => $this->due_date, null),
            'currency' => $this->currency,
            'quantity' => $this->quantity,
            'paid_date' => $this->paid_date,
        ];
    }
}
