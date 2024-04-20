<?php

namespace App\Http\Requests;

use App\Rules\BranchIdRule;
use App\Rules\PasswordRule;
use App\Rules\phoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'email' => 'required|email|max:30|unique:users,email',
            'role_id' => [
                'required',
                'in:1,2,3,4',
                new BranchIdRule($this->input('branch_id')),
            ],
            'branch_id' => [
                'exists:branches,id',
            ],
            'city_id' => 'required|exists:cites,id',
            'birth_date' => 'required',
            'gender' => 'required|boolean',
            'phone' => [
                'required',
                new phoneNumberRule(),
            ],
            'password' => [
                'required',
                'unique:users,phone',
                'confirmed',
                new PasswordRule(),

            ],
        ];
    }
}
