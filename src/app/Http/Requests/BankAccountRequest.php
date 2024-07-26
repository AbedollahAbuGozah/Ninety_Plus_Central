<?php

namespace App\Http\Requests;


use App\Rules\ChaptersBelongToModule;

class BankAccountRequest extends BaseFormRequest
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
        $rules = [];

        if ($this->isStore()) {
            $rules = [
                'account_holder_name' => 'required|string',
                'routing_number' => 'required|string',
                'account_number' => 'required|string',
                'user_id' => 'required|exists:users,id'
            ];
        }

        if ($this->isUpdate()) {
            $rules = [

            ];
        }


        return $rules;
    }
}
