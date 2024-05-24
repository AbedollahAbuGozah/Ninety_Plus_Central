<?php

namespace App\Http\Requests;

use App\constants\CourseStatusOptions;
use Illuminate\Foundation\Http\FormRequest;

class ChangeCourseStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in' . implode(',', CourseStatusOptions::options())
        ];
    }
}
