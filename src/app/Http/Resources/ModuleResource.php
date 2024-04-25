<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->whenLoaded('country', function () {
                return  ($this->country);
            }, $this->country_id),
            'branch' => $this->whenLoaded('branch', function () {
                return  ($this->branch);
            }, $this->country_id),

        ];
    }
}
