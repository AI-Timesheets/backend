<?php

namespace App\Helpers;

class Random {

    // Generates a random key until the truthFunc returns false.
    public static function stringWhereNot($length, $truthFunc) {
        $key = \Str::random($length);

        while ($truthFunc($key)) {
            $key = \Str::random($length);
        }

        return $key;
    }
}
