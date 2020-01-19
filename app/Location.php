<?php

namespace App;

/**
 * Class Location
 * @package App
 *
 * @property string $name;
 * @property int $company_id;
 * @property \App\Company $company;
 * @property \App\CompanyEmployee[] $companyEmployees;
 * @property \App\ClockInLog[] $clockInLogs;
 * @property string $country;
 * @property string $state;
 * @property string $city;
 * @property string $zip_code;
 * @property string $address;
 */
class Location extends BaseModel {
    protected $fillable = [
        'name',
        'company_id',
        'country',
        'state',
        'city',
        'zip_code',
        'address',
    ];

    public function company() {
        return $this->belongsTo("\App\Company");
    }

    public function companyEmployees() {
        return $this->hasMany("\App\CompanyEmployees");
    }

    public function clockInLogs() {
        return $this->hasMany("\App\ClockInLog");
    }
}
