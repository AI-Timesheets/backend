<?php

namespace App\Services;

use App\Company;
use App\CompanyEmployee;
use App\Helpers\Random;
use App\Location;
use App\Repositories\CompanyEmployeeRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\LocationRepository;
use App\Repositories\UserRepository;
use App\User;

class CompanyService {
    public static function getUserCompanies(User $user) {
        return Company::where("owner_user_id", $user->id)->get();
    }

    public static function getCompany($companyId): Company {
        return Company::where("id", $companyId)->firstOrFail();
    }

    public static function getCompanyLocations($companyId) {
        return Location::where("company_id", $companyId)->get();
    }

    public static function getCompanyLocation($companyId, $locationId): Location {
        return Location::where("id", $locationId)->where("company_id", $companyId)->firstOrFail();
    }

    public static function getCompanyEmployees($companyId) {
        return CompanyEmployee::where("company_id", $companyId)->get();
    }

    public static function getCompanyEmployee($companyId, $employeeId) {
        return CompanyEmployee::where("id", $employeeId)->where("company_id", $companyId)->firstOrFail();
    }

    public static function createCompany(User $user, $name) {
        $company = new Company();
        $company->name = $name;
        $company->owner_user_id = $user->id;
        $company->company_code = Random::stringWhereNot(5, function($code) {
            Company::where("company_code", $code)->exists();
        });
        $company->save();
        return $company;
    }

    public static function updateCompany(Company $company, $name) {
        $company->name = $name;
        $company->save();
        return $company;
    }

    public static function createCompanyLocation(Company $company, $name) {
        $location = new Location();
        $location->company_id = $company->id;
        $location->name = $name;
        $location->save();
        return $location;
    }

    public static function updateCompanyLocation(Location $location, $name) {
        $location->name = $name;
        $location->save();
        return $location;
    }

    public static function createCompanyEmployee(Location $location, $firstName, $lastName, $hourlyWage) {
        $employee = new CompanyEmployee();
        $employee->location_id = $location->id;
        $employee->first_name = $firstName;
        $employee->last_name = $lastName;
        $employee->hourly_wage = $hourlyWage;
        $employee->save();
        return $employee;
    }

    public static function updateCompanyEmployee(CompanyEmployee $employee, $firstName, $lastName, $hourlyWage) {
        $employee->first_name = $firstName;
        $employee->last_name = $lastName;
        $employee->hourly_wage = $hourlyWage;
        $employee->save();
        return $employee;
    }

    public static function setCompanyEmployeePhoto(CompanyEmployee $employee, Photo $photo) {
        $employee->photo_id = $photo->id;
        $employee->save();
        return $employee;
    }

    public static function deleteCompanyEmployeePhoto(CompanyEmployee $employee) {
        $employee->photo_id = null;
        $employee->save();
        return $employee;
    }
}
