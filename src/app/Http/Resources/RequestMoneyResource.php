<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class RequestMoneyResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->full_name,
            'requested_date' => $this->properties['balance_info']['requested_date'],
            'amount' => $this->balance,
        ];
    }
}
