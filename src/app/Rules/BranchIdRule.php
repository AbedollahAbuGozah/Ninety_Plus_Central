<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BranchIdRule implements ValidationRule
{
    public function __construct(protected $branchID)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->branchID && $value == 4)
        {
            $fail('The branch_id is required');
        }
    }
}
