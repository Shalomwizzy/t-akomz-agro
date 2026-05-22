<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MeaningfulDescription implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen(trim($value)) < 20) {
            $fail('Please provide a meaningful description explaining what was bought, why it was needed, and where it will be used (minimum 20 characters).');
            return;
        }

        if (preg_match('/^(food|work|expense|payment|buy|bought|purchase|misc|other|item|things?)$/i', trim($value))) {
            $fail('Please provide a meaningful description explaining what was bought, why it was needed, and where it will be used (minimum 20 characters).');
        }
    }
}
