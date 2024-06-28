<?php

namespace App\Http\Requests;


use App\Models\Chapter;

class LectureRequest extends BaseFormRequest
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

        $course_id = $this->input('course_id');


        if ($this->isStore()) {
            $rules = [
                'name' => 'required|string|max:60|min:10',
                'description' => 'sometimes|required',
                'course_id' => 'required|exists:courses,id',
                'starts_at' => 'required|date|after:now',
                'chapter_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($course_id) {
                        $valid = Chapter::whereHas('module.courses', function ($query) use ($course_id) {
                            $query->where('courses.id', $course_id);
                        })->where('id', $value)->exists();

                        if (!$valid) {
                            $fail('The selected chapter is invalid.');
                        }
                    }
                ]
            ];
        }

        if ($this->isUpdate()) {
            $rules = [
                'name' => 'sometimes|required|string|max:60|min:10',
                'description' => 'sometimes|required',
                'course_id' => 'sometimes|required|exists:courses,id',
                'starts_at' => 'sometimes|required|date|after:now',
                'chapter_id' => [
                    'sometimes',
                    'required',
                    function ($attribute, $value, $fail) {
                        $valid = Chapter::whereHas('module.courses', function ($query) {
                                $query->where('courses.id', $this->route('lecture')->id);
                        })->where('id', $value)->exists();

                        if (!$valid) {
                            $fail('The selected chapter is invalid.');
                        }
                    }
                ]
            ];
        }


        return $rules;
    }
}
