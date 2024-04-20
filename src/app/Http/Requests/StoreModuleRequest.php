<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModuleRequest extends FormRequest
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
        logger($this->route('country')['id']);
        $this->merge(['country_id' => $this->route('country')['id']]);
        return [
            'name' => 'required|min:2|max:30',
            'branch_id' => 'required|exists:branches,id',
            'country_id' => 'required|exists:countries,id',
        ];
    }
}
