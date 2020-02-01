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

    static function same($array, $idFn) {
        if (count($array) === 0) {
            return true;
        }

        $id = $idFn($array[0]);

        for ($i = 0; $i < count($array); $i++) {
            $newId = $idFn($array[$i]);
            if ($newId !== $id) {
                return false;
            }
        }

        return true;
    }

    static function timestamp() {
        return date("Y-m-d H:i:s");
    }

    static function ISOTimestamp() {
        return date("c");
    }
}
