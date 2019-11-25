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
 */
class Location extends BaseModel {
    protected $fillable = [
        'name',
        'company_id',
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
