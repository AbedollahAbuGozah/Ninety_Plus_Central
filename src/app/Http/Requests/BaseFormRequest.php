<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseFormRequest extends FormRequest
{

    public function authorize(): bool
    {
    }
    public function rules(): array
    {
    }

    public function isUpdate()
    {
        return $this->method() == 'PUT' ;
    }
    public function isStore()
    {
        return $this->method() == 'POST' ;
    }

}
