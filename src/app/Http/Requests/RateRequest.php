<?php

namespace App\Http\Requests;


use App\Rules\CourseChapterRule;

class RateRequest extends BaseFormRequest
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

        $this->merge(['user_id' => auth()->id()]);
        if ($this->isStore()) {
            $rules = [
                'rate_value' => 'required|numeric|in:0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
                'user_id' => 'required',
            ];
        }

        if ($this->isUpdate()) {
            $rules = [
                'rate_value' => 'required|string',
                'user_id' => 'required',
            ];
        }


        return $rules;
    }
}
