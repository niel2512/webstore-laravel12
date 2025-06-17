<?php

namespace App\Rules;

use App\Services\ShippingMethodService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidShippingHash implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $found = app(ShippingMethodService::class)->getShippingMethod($value);
        if (!$found) {
            $fail("Shipping Method Not Valid!");
        }
    }
}
