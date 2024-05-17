<?php

namespace App\Http\Requests;

use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $old_password = $this->route('user')->password;
        return [
            'old_password' => 'required|in' . $old_password,
            'password' => [
                'required',
                'confirmed',
                new PasswordRule()
            ]
        ];
    }
}
