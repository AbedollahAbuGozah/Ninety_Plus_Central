<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'city_id' => $this->whenLoaded('city', function () {
                return [
                    'id' => $this->city_id,
                    'name' => $this->city->name
                ];
            }, $this->city_id),
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
        'email_verified' => $this->email_verified_at ?? false,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'roles' => $this->roles->pluck('name'),
        ];
    }
}
