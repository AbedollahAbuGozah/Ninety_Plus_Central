<?php

namespace App\Http\Requests;



class PackageRequest extends BaseFormRequest
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
                'name' => 'required|string',
                'description' => 'required|string|min:10|max:250',
                'price' => 'required|numeric',
                'chapters' => 'required|array',
                'chapters.*' => 'required|array',
                'chapters.*.id' => 'required|exists:chapters,id',
                'chapters.*.exams_count' => 'sometimes|required|integer',
                'discount' => 'sometimes|required|numeric|min:1|max:100',
                'discount_expires_at' => 'required_with:discount|date',
                'module_id' => 'required|exists:modules,id',
                'cover_image' => 'sometimes|required|mimes:jpeg,png,jpg,gif,svg,webp',
                'gifted_points' => 'required|integer'
            ];
        }

        if ($this->isUpdate()) {
            $rules = [
                'name' => 'sometimes|required|string',
                'description' => 'sometimes|required|string|min:10|max:250',
                'price' => 'sometimes|required|numeric',
                'chapters' => 'sometimes|required|array',
                'chapters.*' => 'sometimes|required|array',
                'chapters.*.id' => 'sometimes|required|exists:chapters,id',
                'chapters.*.exams_count' => 'sometimes|required|integer',
                'discount' => 'sometimes|required|numeric|min:1|max:100',
                'discount_expires_at' => 'required_with:discount|date',
                'module_id' => 'sometimes|exists:modules,id',
                'cover_image' => 'sometimes|required|mimes:jpeg,png,jpg,gif,svg,webp',
                'gifted_points' => 'sometimes|required|integer'
            ];
        }


        return $rules;
    }
}
