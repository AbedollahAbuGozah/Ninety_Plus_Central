<?php

namespace App\Http\Requests;


use App\Rules\ChaptersBelongToModule;

class ChapterRequest extends BaseFormRequest
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
               'title' => 'required|string',
                'module_id' => 'required|exists:modules,id'
            ];
        }

        if ($this->isUpdate()) {
            $rules = [
                'title' => 'sometimes|required|string',
                'module_id' => 'sometimes|required|exists:modules,id'
            ];
        }


        return $rules;
    }
}
