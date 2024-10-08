<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumberRule;
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
            'city_id' => 'sometimes|required|exists:cities,id',
            'birth_date' => 'sometimes|required',
            'gender' => 'sometimes|required|boolean',
            'profile_image' => 'sometimes|required|image',
            'about' => 'sometimes|required',
            'phone' => [
                'sometimes',
                'required',
                new PhoneNumberRule(),//TODO
            ],
        ];
    }
}
