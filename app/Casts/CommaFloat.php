<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Legacy rows store these amounts as comma-formatted strings (e.g. "1,234.56"),
 * which throws a TypeError under PHP 8 arithmetic/number_format(). Normalise on read.
 */
class CommaFloat implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        return (float) str_replace(',', '', $value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return (float) str_replace(',', '', $value ?? 0);
    }
}
