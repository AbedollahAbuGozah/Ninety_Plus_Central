<?php

namespace App\Http\Requests;

use App\Rules\phoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|required|min:3|max:50',
            'last_name' => 'sometimes|required|min:3|max:50',
            'email' => 'sometimes|required|email|max:30',
            'city_id' => 'sometimes|required|exists:cites,id',
            'birth_date' => 'sometimes|required',
            'gender' => 'sometimes|required|boolean',
            'profile_image' => 'sometimes|image',
            'phone' => [
                'sometimes',
                'required',
                new phoneNumberRule(),//TODO
            ],
        ];
    }
}
