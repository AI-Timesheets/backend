<?php

namespace App\Helpers;

class Functions
{
    static function ifNull($value, $default)
    {
        if ($value === null || !$value) {
            return $default;
        } else {
            return $value;
        }
    }
}
