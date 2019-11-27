<?php

namespace App;

/**
 * Class Company
 * @package App
 *
 * @property string $name;
 * @property string $company_code;
 * @property int $owner_user_id;
 * @property \App\User $owner;
 * @property \App\Location[] $locations;
 * @property \App\CompanyEmployee[] $employees;
 */
class Company extends BaseModel {
    protected $fillable = [
        'name',
        'company_code',
        'owner_user_id',
    ];

    public function owner() {
        return $this->belongsTo("\App\User", "owner_user_id");
    }

    public function locations() {
        return $this->hasMany("\App\Location");
    }

    public function employees() {
        return $this->hasMany("\App\CompanyEmployee");
    }

    public function hasLocation($locationId) {
        return Location::where("id", $locationId)->where("company_id", $this->id)->exists();
    }

    public function hasEmployee($employeeId) {
        return CompanyEmployee::where("id", $employeeId)->where("company_id", $this->id)->exists();
    }
}
