<?php

namespace App\Http\Requests;


use App\Rules\CourseChapterRule;

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

        if ($this->isStore()) {
            $rules = [
                'title' => 'required|max:190',
                'period' => 'required|in:first,second,third',
                'instructor_id' => 'required|exists:users,id',
                'module_id' => 'required|exists:modules,id',
                'description' => 'required|string',
                'chapters' => [
                    'sometimes',
                    'required',
                    'array',
                    new CourseChapterRule($this->module_id)
                ],
                'starts_at' => 'required|date',
                'ends_at' => 'required|date|gte:starts_at',
                'chapters.*' => 'required|exists:chapters,id',
                'cover_image' => 'sometimes|required|image',
                'intro_video' => 'sometimes|required|video',
                'welcome_message' => 'sometimes|required|string',
                'ending_message' => 'sometimes|required|string'
            ];
        }

        if ($this->isUpdate()) {
            $rules = [
                'title' => 'sometimes|required|max:190',
                'period' => 'sometimes|required|in:first,second,third',
                'module_id' => 'sometimes|required|exists:modules,id',
                'chapters' => [
                    'sometimes',
                    'required',
                    'array',
                    new CourseChapterRule($this->module_id)
                ],
                'chapters.*' => 'required|exists:chapters,id',
                'description' => 'sometimes|required|string',
                'cover_image' => 'sometimes|required|image',
                'intro_video' => 'sometimes|required|video',
                'welcome_message' => 'sometimes|required|string',
                'ending_message' => 'sometimes|required|string'
            ];
        }


        return $rules;
    }
}
