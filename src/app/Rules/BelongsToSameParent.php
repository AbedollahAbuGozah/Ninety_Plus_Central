<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class BelongsToSameParent implements ValidationRule
{
    protected $parentModel;
    protected $relatedModel;
    protected $parentForeignKey;
    protected $validatedModel;


    public function __construct($parentModel, $relatedModel, $validatedModel, $parentForeignKey, $routeParamName)
    {
        $this->parentModel = $parentModel;
        $this->relatedModel = $relatedModel;
        $this->parentForeignKey = $parentForeignKey;
        $this->validatedModel = request()->route($routeParamName) ?: new $validatedModel();
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $relatedIds = $this->reformatRelatedArray($value);

        $parentForeignKey = $this->parentForeignKey;

        $expectedParentId = $this->getExpectedParentId();

        $distinctParentIds = $this->relatedModel::whereIn('id', $relatedIds)
            ->select($parentForeignKey)
            ->distinct()
            ->pluck($parentForeignKey);

        if ($distinctParentIds->count() > 1 || $distinctParentIds->first() != $expectedParentId) {
            $fail("The {$attribute} must belong to the specified " . class_basename($this->parentModel) . " entity.");
        }
    }

    private function reformatRelatedArray($value): array
    {
        return match (true) {
            is_array($value[0]) => $this->handleRelatedWithPivots($value),
            default => $this->handleRelatedWithoutPivots($value)
        };
    }

    private function handleRelatedWithPivots($value): array
    {
        return array_column($value, 'id');
    }

    private function handleRelatedWithoutPivots($value)
    {
        return $value;
    }

    private function getExpectedParentId()
    {
        return !request()->has($this->parentForeignKey) ?
            $this->validatedModel->{$this->parentForeignKey} :
            request()->input($this->parentForeignKey);
    }
}
