<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class inFuture implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Parse the input date
        $eventDate = Carbon::parse($value)->startOfDay();

        // Today + 8 days
        $minAllowedDate = now()->addDays(8)->startOfDay();

        if(! $eventDate->gte($minAllowedDate)){
            $fail("the date of the event should be after 8 days at least");
        }
    }
}
