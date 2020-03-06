<?php


namespace App;

/**
 * Class GeographicLocation
 * @package App
 *
 * @property double $latitude;
 * @property double $longitude;
 * @property string $address;
 * @property string $state;
 * @property string $city;
 * @property string $country;
 * @property int $area_code;
 * @property int $area_code_suffix;
 */
class GeographicLocation extends BaseModel
{
    protected $table = "geographic_locations";
    protected $fillable = [
        "latitude",
        "longitude",
        "address",
        "state",
        "city",
        "country",
        "area_code",
        "area_code_suffix",
    ];
}
