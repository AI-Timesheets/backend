<?php

namespace App\Services;

use App\GeographicLocation;

class GeolocationService
{
    const GOOGLE_API_URL = "https://maps.googleapis.com/maps/api/geocode/json";

    private static function GetAPIKey() {
        $key = env("GOOGLE_API_KEY");

        if (!isset($key)) {
            throw new \Exception("Missing env variable: GOOGLE_API_KEY");
        }

        return $key;
    }

    private static function ReverseGeoLocate($latitude, $longitude): GeographicLocation {
        $key = self::GetAPIKey();
        $latlng = "{$latitude},{$longitude}";
        $url = self::GOOGLE_API_URL."?latlng=".$latlng."&key=".$key;

        \Log::info($url);

        $results = json_decode(file_get_contents($url));

        \Log::info(json_encode($results));

        if ($results->status !== "OK") {
            throw new \Exception("Failed to reverse geolocate via Google API");
        }

        $result = $results->results[0];

        $geoLocation = new GeographicLocation();
        $geoLocation->latitude = $latitude;
        $geoLocation->longitude = $longitude;

        $streetNumber = null;
        $streetName = null;

        foreach ($result->address_components as $component) {
            if (in_array("street_number", $component->types)) {
                $streetNumber= $component->short_name;
            }

            if (in_array("route", $component->types)) {
                $streetName = $component->short_name;
            }

            if (in_array("locality", $component->types)) {
                $geoLocation->city = $component->short_name;
            }

            if (in_array("administrative_area_level_1", $component->types)) {
                $geoLocation->state = $component->short_name;
            }

            if (in_array("country", $component->types)) {
                $geoLocation->country = $component->short_name;
            }

            if (in_array("postal_code", $component->types)) {
                $geoLocation->area_code = $component->short_name;
            }

            if (in_array("postal_code_suffix", $component->types)) {
                $geoLocation->area_code_suffix = $component->short_name;
            }
        }

        $geoLocation->address = "{$streetNumber} {$streetName}";

        $geoLocation->save();

        return $geoLocation;
    }

    public static function GetGeolocationByCoordinates($latitude, $longitude): GeographicLocation {
        $location = GeographicLocation::where("latitude", $latitude)
            ->where("longitude", $longitude)
            ->first();

        if ($location) {
            return $location;
        }

        return self::ReverseGeoLocate($latitude, $longitude);
    }
}
