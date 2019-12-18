<?php

namespace App\Helpers;

class Random {

    // Remove some of the letters which will be confusing.
    const LETTERS = ['A','B','C','D','E','F','G','H','J','K','M','N','P','Q','R','S','T','W','X','Y','Z'];

    const NUMBERS = ['2', '3', '4', '5', '6', '7', '8', '9'];

    public static function integer($min, $max) {
        return mt_rand($min, $max - 1);
    }

    public static function alphanumeric($length) {
        $key = "";

        $letterCount = count(self::LETTERS);
        $numberCount = count(self::NUMBERS);

        for ($i = 0; $i < $length; $i++) {
            if (Random::integer(0, 2) === 0) {
                $key .= self::LETTERS[Random::integer(0, $letterCount)];
            } else {
                $key .= self::NUMBERS[Random::integer(0, $numberCount)];
            }
        }

        return $key;
    }

    // Generates a random key until the truthFunc returns false.
    public static function stringWhereNot($length, $truthFunc) {
        $key = Random::alphanumeric($length);

        while ($truthFunc($key)) {
            $key = Random::alphanumeric($length);
        }

        return $key;
    }
}
