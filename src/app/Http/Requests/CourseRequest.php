<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends BaseFormRequest
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

        if ($this->isUpdate()) {
            $rules = [
                'title' => 'sometimes|required|max:190',
                'period' => 'sometimes|required|in:first,second,third',
                'module_id' => 'sometimes|required|exists:modules,id',
                'chapters' => 'sometimes|required|array',
                'chapters.*' => 'required|exists:chapters,id'
            ];
        }
        if ($this->isStore()) {
            $rules = [
                'title' => 'required|max:190',
                'period' => 'required|in:first,second,third',
                'instructor_id' => 'required|exists:users,id',
                'module_id' => 'required|exists:modules,id',
                'chapters' => 'required|array',
                'chapters.*id' => 'required|exists:chapters,id'
            ];
        }
        return $rules;
    }
}
