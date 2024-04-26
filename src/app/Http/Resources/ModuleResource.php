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
                return [
                    'id' => $this->country_id,
                    'name' => $this->country->name
                ];
            }, $this->country_id),
            'branch' => $this->whenLoaded('branch', function () {
                return [
                    'id' => $this->branch_id,
                    'name' => $this->branch->name
                ];
            }, $this->branch_id),
            'courses' => $this->whenLoaded('courses', $this->courses->pluck('id', 'name'))];
    }
}
