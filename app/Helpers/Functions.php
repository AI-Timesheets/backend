<?php

namespace App\Helpers;

class Functions
{
    const DATESTAMP_FMT = "Y-m-d";
    const DATETIMESTAMP_FMT = "Y-m-d H:i:s";
    const TIMESTAMP_FMT = "H:i:s";

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

    public static function deltaTime($date1, $date2) {
        return strtotime($date2) - strtotime($date1);
    }

    // Converts a generic time string to a SQL compliant one.
    public static function toTimestamp($date, $fmt = self::DATETIMESTAMP_FMT) {
        return date($fmt, strtotime($date));
    }

    // Returns a list of dates between two dates, exclusively.
    public static function dateRange($start, $end, $fmt = self::DATESTAMP_FMT) {
        $start = strtotime($start);
        $end = strtotime($end);

        $dates = [];

        for ($curr = $start; $curr < $end; $curr += 60 * 60 * 24) {
            $dates[] = date($fmt, $curr);
        }

        return $dates;
    }

    static function timestamp($fmt = self::DATETIMESTAMP_FMT) {
        return date($fmt);
    }

    static function ISOTimestamp() {
        return date("c");
    }
}
