<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CityResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
