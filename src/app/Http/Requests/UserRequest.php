<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Rules\PasswordRule;
use App\Rules\phoneNumberRule;

class UserRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];
        $studentId = Role::where('name', 'student')->value('id');

        if ($this->isStore()) {
            $rules = [
                'first_name' => 'required|min:3|max:50',
                'last_name' => 'required|min:3|max:50',
                'email' => 'required|email|max:100|unique:users,email',
                'role_id' => 'required|in:' . implode(',', Role::getAllowedRegister()->pluck('id')->flatten()),
                'branch_id' => 'required_if:role_id,' . $studentId . '|exists:branches,id',
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

        if ($this->isUpdate()) {
            $rules = [
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
            ];
        }

        return $rules;
    }
}
