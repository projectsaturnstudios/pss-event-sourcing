<?php

namespace ProjectSaturnStudios\EventSourcing\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

abstract class HasNoEvents implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    public string $storable_event_model;

    abstract protected function getFailMessage() : string;

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if($this->storable_event_model::whereAggregateUuid($this->getUuid($attribute, $value))->exists())
        {
            $fail($this->getFailMessage());
        }

    }

    protected function getUuid(string $attribute, mixed $value) : string
    {
        return new_uuid($value);
    }
}
