<?php

namespace App\Services;

use App\Company;
use App\CompanyEmployee;
use App\Helpers\Random;
use App\Helpers\Functions;
use App\Location;
use App\Repositories\CompanyEmployeeRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\LocationRepository;
use App\Repositories\UserRepository;
use App\User;

class CompanyService {
    public static function getUserCompanies(User $user) {
        return Company::where("owner_user_id", $user->id)->with(['locations'])->get();
    }

    public static function getCompany($companyId): Company {
        $company = Company::where("id", $companyId)->with(['locations'])->first();
        if (!$company) {
            throw new \Exception("Company does not exist");
        }
        return $company;
    }

    public static function getCompanyLocations($companyId) {
        return Location::where("company_id", $companyId)->get();
    }

    public static function getCompanyLocation($companyId, $locationId): Location {
        $location = Location::where("id", $locationId)->where("company_id", $companyId)->first();
        if (!$location) {
            throw new \Exception("Location does not exist");
        }
        return $location;
    }

    public static function getCompanyEmployees($companyId) {
        return CompanyEmployee::where("company_id", $companyId)->with(['location', 'company'])->get();
    }

    public static function getCompanyEmployee($companyId, $employeeId) {
        $employee = CompanyEmployee::where("id", $employeeId)->where("company_id", $companyId)->with(['location', 'company'])->first();
        if (!$employee) {
            throw new \Exception("Employee does not exist");
        }
        return $employee;
    }

    public static function createCompany(User $user, $name) {
        $company = new Company();
        $company->name = $name;
        $company->owner_user_id = $user->id;
        $company->company_code = Random::stringWhereNot(5, function($code) {
            Company::where("company_code", $code)->exists();
        });
        $company->save();

        CompanyService::createCompanyLocation($company, $company->name." HQ", null, null, null, null, null);

        return $company;
    }

    public static function updateCompany(Company $company, $name) {
        $company->name = $name;
        $company->save();
        return $company;
    }

    public static function createCompanyLocation(Company $company, $name, $country, $state, $city, $zipCode, $address) {
        $location = new Location();
        $location->company_id = $company->id;
        $location->name = $name;
        $location->country = Functions::ifNull($country, "");
        $location->state = Functions::ifNull($state, "");
        $location->city = Functions::ifNull($city, "");
        $location->zip_code = Functions::ifNull($zipCode, "");
        $location->address = Functions::ifNull($address, "");
        $location->save();
        return $location;
    }

    public static function updateCompanyLocation(Location $location, $name, $country, $state, $city, $zipCode, $address) {
        $location->name = $name;
        $location->country = Functions::ifNull($country, "");
        $location->state = Functions::ifNull($state, "");
        $location->city = Functions::ifNull($city, "");
        $location->zip_code = Functions::ifNull($zipCode, "");
        $location->address = Functions::ifNull($address, "");
        $location->save();
        return $location;
    }

    public static function createCompanyEmployee($locationId, $firstName, $lastName, $hourlyWage, $isAdmin, $loginCode) {

        $location = Location::where("id", $locationId)->with("company")->first();

        if (!$location) {
            throw new \Exception("Location does not exist");
        }

       if (CompanyEmployee::where("company_id", $location->company->id)->where("login_code", $loginCode)->exists()) {
           throw new \Exception("Existing employee already has this login code");
       }

        $employee = new CompanyEmployee();
        $employee->company_id = $location->company_id;
        $employee->location_id = $locationId;
        $employee->first_name = $firstName;
        $employee->last_name = $lastName;
        $employee->hourly_wage = $hourlyWage;
        $employee->is_admin = $isAdmin;
        $employee->login_code = $loginCode;
        $employee->status = "ACTIVE";
        $employee->save();
        return $employee;
    }

    public static function updateCompanyEmployee($employeeId, $locationId, $firstName, $lastName, $hourlyWage, $isAdmin, $loginCode) {

        $employee = CompanyEmployee::where("id", $employeeId)->with("company")->first();

        $location = Location::where("id", $locationId)->first();

        if (!$location) {
            throw new \Exception("Location does not exist");
        }

        if (!$employee) {
            throw new \Exception("Employee does not exist");
        }

        if (CompanyEmployee::where("company_id", $employee->company->id)->where("login_code", $loginCode)->where("id", "<>", $employeeId)->exists()) {
            throw new \Exception("Existing employee already has this login code");
        }

        $employee->location_id = $location->id;
        $employee->first_name = $firstName;
        $employee->last_name = $lastName;
        $employee->hourly_wage = $hourlyWage;
        $employee->is_admin = $isAdmin;
        $employee->login_code = $loginCode;
        $employee->status = "ACTIVE";
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

    public static function deleteLocation(Location $location) {
        $locationCount = Location::where("company_id", $location->company_id)->count();

        if ($locationCount === 1) {
            throw new \Exception("Must have at least one location");
        }

        CompanyService::deactivateLocationEmployees($location);
        $location->delete();
    }

    public static function deactivateLocationEmployees(Location $location) {
        CompanyEmployee::where('location_id', $location->id)->update(['status' => 'INACTIVE']);
    }
}
