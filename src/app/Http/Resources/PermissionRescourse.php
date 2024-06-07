<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionRescourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'view_access' => $this->view_access,
            'modify_access' => $this->modify_access,
            'delete_access' => $this->delete_access,
            'manage_access' => $this->manage_access,
        ];
    }
}
