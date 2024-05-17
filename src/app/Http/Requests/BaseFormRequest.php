<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseFormRequest extends FormRequest
{
    public function isUpdate()
    {
        return $this->method() == 'PUT' ;
    }
    public function isStore()
    {
        return $this->method() == 'POST' ;
    }

}
