<?php

namespace App;

/**
 * Class CompanyEmployee
 * @package App
 *
 * @property string $first_name;
 * @property string $last_name;
 * @property bool $is_admin;
 * @property int $login_code;
 * @property double $hourly_wage;
 * @property int $company_id;
 * @property int $location_id;
 * @property int $photo_id;
 * @property \App\Company $company;
 * @property \App\Location $location;
 * @property \App\Photo $photo;
 * @property \App\EmployeeFaces $faces;
 * @property \App\ClockInLog[] $clockInLogs;
 */
class CompanyEmployee extends BaseModel {
    protected $fillable = [
        'first_name',
        'last_name',
        'is_admin',
        'login_code',
        'hourly_wage',
        'company_id',
        'location_id',
        'photo_id',
    ];

    public function company() {
        return $this->belongsTo("\App\Company");
    }

    public function location() {
        return $this->belongsTo("\App\Location");
    }

    public function photo() {
        return $this->belongsTo("\App\Photo");
    }

    public function faces() {
        return $this->hasMany('\App\EmployeeFaces', 'company_employee_id');
    }

    public function clockInLogs() {
        return $this->hasMany("\App\ClockInLog");
    }
}
