<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Rules\PasswordRule;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\Facades\Hash;

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
                'role_id' => 'required|in:' . implode(',', Role::getAllowedRegister()->pluck('id')->flatten()->toArray()),
                'branch_id' => 'required_if:role_id,' . $studentId . '|exists:branches,id',
                'city_id' => 'required|exists:cities,id',
                'birth_date' => 'required',
                'profile_image' => 'sometimes|required|image',
                'gender' => 'required|boolean',
                'phone' => [
                    'unique:users,phone',
                    'required',
                    new PhoneNumberRule(),
                ],
                'password' => [
                    'required',
                    'confirmed',
                    new PasswordRule(),
                ],
            ];

            //this check added for admin so admin can create user from any role and if it's not authenticated it will be registered by the allowed rule

            if (!auth()->check()) {
                $rules['role_id'] .= '|in:' . implode(',', Role::getAllowedRegister()->pluck('id')->toArray());
            }
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
                'profile_image' => 'sometimes|required|image',
                'city_id' => 'sometimes|required|exists:cities,id',
                'birth_date' => 'sometimes|required',
                'gender' => 'sometimes|required|boolean',
                'about' => 'sometimes|required',
                'phone' => [
                    'sometimes',
                    'required',
                    new PhoneNumberRule(),//TODO
                ],
                'current_password' => [
                    'required_with:password',
                    function ($attribute, $value, $fail) {
                        if (!Hash::check($value, $this->user()->password)) {
                            $fail('The current password is incorrect.');
                        }
                    },
                ],
                'password' => [
                    'confirmed',
                    new PasswordRule(),
                ],
            ];
        }

        return $rules;
    }
}
