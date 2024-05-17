<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];

        if ($this->isStore()) {
            $this->merge(['country_id' => $this->route('country')['id']]);
            $rules = [
                'name' => 'required|min:2|max:30',
                'branch_id' => 'required|exists:branches,id',
                'country_id' => 'required|exists:countries,id',
            ];
        }

        if ($this->isUpdate()) {
            $rules = [
                'name' => 'sometimes|required|min:2|max:30',
                'branch_id' => 'sometimes|required|exists:branches,id',
            ];

        }

        return $rules;
    }
}
