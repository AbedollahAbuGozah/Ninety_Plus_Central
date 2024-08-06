<?php

namespace App\Http\Requests;


use App\Models\Chapter;
use App\Models\Module;
use App\Models\Package;
use App\Rules\BelongsToSameParent;

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
        logger(__METHOD__);
        logger($this);

        $rules = [];

        if ($this->isStore()) {
            $rules = [
                'name' => 'required|string',
                'description' => 'required|string|min:10|max:250',
                'price' => 'required|numeric',
                'chapters.*' => 'required|array',
                'chapters.*.id' => [
                    'required',
                    'exists:chapters,id',
                ],
                'chapters' => [
                    'required',
                    'array',
                    new BelongsToSameParent(Module::class,Chapter::class ,Package::class,'module_id', null)
                ],
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
                'chapters.*' => 'required|array',
                'chapters.*.id' => [
                    'required',
                    'exists:chapters,id',
                ],
                'chapters' => [
                    'required',
                    'array',
                    new BelongsToSameParent(Module::class,Chapter::class ,Package::class,'module_id', 'package')
                ],
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
