<?php

namespace App\Http\Requests;

use App\Rules\BranchIdRule;
use App\Rules\PasswordRule;
use App\Rules\phoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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

            'branch_id' => [
                'sometimes',
                'exists:branches,id',
            ],
            'city_id' => 'sometimes|required|exists:cites,id',
            'birth_date' => 'sometimes|required',
            'gender' => 'sometimes|required|boolean',
            'phone' => [
                'sometimes',
                'required',
                new phoneNumberRule(),//TODO
            ],
            'password' => [
                'sometimes',
                'required',
                'confirmed',
                new PasswordRule()
            ]
        ];
    }
}
