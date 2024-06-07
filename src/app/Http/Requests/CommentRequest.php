<?php

namespace App\Http\Requests;


use App\Rules\CourseChapterRule;

class CommentRequest extends BaseFormRequest
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
                'content' => 'required|string',
                'user_id' => 'required',
            ];
        }

        if ($this->isUpdate()) {
            $rules = [
                'content' => 'required|string',
                'user_id' => 'required',
            ];
        }


        return $rules;
    }
}
