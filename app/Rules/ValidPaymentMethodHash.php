<?php

namespace App\Rules;

use App\Services\PaymentMethodQueryService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPaymentMethodHash implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = app(PaymentMethodQueryService::class);
        $found = $query->getPaymentMethodByHash($value);

        if (!$found) {
            $fail("Payment Method Not Valid!");
        }
    }
}
