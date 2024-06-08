<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rate_value' => $this->rate_value,
            'user_id' => $this->user_id,
            'ratable_id' => $this->ratable_id,
        ];
    }
}
