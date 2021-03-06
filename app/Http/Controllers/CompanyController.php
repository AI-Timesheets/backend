<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClockInLog;
use App\Company;
use App\CompanyEmployee;
use App\Http\Requests\BackendAuthorizedRequest;
use App\Http\Requests\CreateCompanyEmployeeRequest;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\CreateInitialCompanyRequest;
use App\Http\Requests\CreateLocationRequest;
use App\Services\ClockInService;
use App\Services\CompanyService;

class CompanyController extends Controller {

    public function hasInitialCompany(BackendAuthorizedRequest $request) {
        return $this->handle(function() use ($request) {

            $companies = CompanyService::getUserCompanies($request->user);

            if (count($companies) === 0) {
                throw new \Exception("User has not registered company");
            }

            return $companies[0];
        });
    }

    public function createInitialCompany(CreateInitialCompanyRequest $request) {
        return $this->handle(function() use ($request) {
           $company = CompanyService::createCompany($request->user, $request->companyName);
           $location = CompanyService::createCompanyLocation($company, $request->locationName, null, null, null, null, null);

           return [
               'company' => $company,
               'location' => $location,
           ];
        });
    }

    public function company($id) {
        return $this->handle(function() use ($id) {
           return CompanyService::getCompany($id);
        });
    }

    public function companies(BackendAuthorizedRequest $request) {
        return $this->handle(function() use ($request) {
            return CompanyService::getUserCompanies($request->user);
        });
    }

    public function createCompany(CreateCompanyRequest $request) {
        return $this->handle(function() use ($request) {
            return CompanyService::createCompany($request->user, $request->name);
        });
    }

    public function updateCompany(CreateCompanyRequest $request, $id) {
        return $this->handleIfOwner($request, $id, function() use ($request, $id) {
            $company = CompanyService::getCompany($id);
            return CompanyService::updateCompany($company, $request->name);
        });
    }

    public function deleteCompany(BackendAuthorizedRequest $request, $id) {
        return $this->handleIfOwner($request, $id, function() use ($request, $id) {
            $company = CompanyService::getCompany($id);
            $company->delete();
        });
    }

    public function locations(BackendAuthorizedRequest $request, $companyId) {
        return $this->handleIfOwner($request, $companyId, function() use ($companyId) {
            return CompanyService::getCompanyLocations($companyId);
        });
    }

    public function location(BackendAuthorizedRequest $request, $companyId, $locationId) {
        return $this->handleIfOwner($request, $companyId, function() use ($companyId, $locationId) {
            return CompanyService::getCompanyLocation($companyId, $locationId);
        });
    }

    public function createLocation(CreateLocationRequest $request, $id) {
        return $this->handleIfOwner($request, $id, function($company) use ($request) {
            return CompanyService::createCompanyLocation($company, $request->name, $request->country, $request->state, $request->city, $request->zipCode, $request->address);
        });
    }

    public function updateLocation(CreateLocationRequest $request, $companyId, $id) {
        return $this->handleIfOwner($request, $companyId, function() use ($request, $companyId, $id) {
            $location = CompanyService::getCompanyLocation($companyId, $id);
            return CompanyService::updateCompanyLocation($location, $request->name, $request->country, $request->state, $request->city, $request->zipCode, $request->address);
        });
    }

    public function deleteLocation(BackendAuthorizedRequest $request, $companyId, $id) {
        return $this->handleIfOwner($request, $companyId, function() use ($request, $companyId, $id) {
            $location = CompanyService::getCompanyLocation($companyId, $id);
            CompanyService::deleteLocation($location);
        });
    }

    public function employee(BackendAuthorizedRequest $request, $companyId, $id) {
        return $this->handleIfOwner($request, $companyId, function() use ($request, $companyId, $id) {
            return CompanyService::getCompanyEmployee($companyId, $id);
        });
    }

    public function employees(BackendAuthorizedRequest $request, $companyId) {
        return $this->handleIfOwner($request, $companyId, function() use ($request, $companyId) {
            return CompanyService::getCompanyEmployees($companyId);
        });
    }

    public function createEmployee(CreateCompanyEmployeeRequest $request, $companyId) {
        return $this->handleIfOwner($request, $companyId, function() use ($request, $companyId) {
            return CompanyService::createCompanyEmployee(
                $request->locationId,
                $request->firstName,
                $request->lastName,
                $request->hourlyWage,
                $request->isAdmin,
                $request->loginCode);
        });
    }

    public function updateEmployee(CreateCompanyEmployeeRequest $request, $companyId, $employeeId) {
        return $this->handleIfOwner($request, $companyId, function() use ($request, $companyId, $employeeId) {
            return CompanyService::updateCompanyEmployee(
                $employeeId,
                $request->locationId,
                $request->firstName,
                $request->lastName,
                $request->hourlyWage,
                $request->isAdmin,
                $request->loginCode);
        });
    }

    public function deleteEmployee(BackendAuthorizedRequest $request, $companyId, $employeeId) {
        return $this->handleIfOwner($request, $companyId, function() use ($request, $companyId, $employeeId) {
            $employee = CompanyService::getCompanyEmployee($companyId, $employeeId);
            $employee->delete();
        });
    }

    public function timeclockLogs(BackendAuthorizedRequest $request, $companyId) {
        return $this->handleIfOwner($request, $companyId, function(Company $company) {
            $locationIds = [];

            foreach ($company->locations as $location) {
                $locationIds[] = $location->id;
            }

            return ClockInLog::whereIn("location_id", $locationIds)
                ->with(['companyEmployee', 'photo', 'photo', 'location', 'clockIn', 'clockOut', 'geographicLocation'])
                ->where("status", ClockInService::SUCCESS)
                ->orderBy("timestamp", "DESC")
                ->get();
        });
    }

    public function clockedInEmployees(Request $request, $companyId) {
        return $this->handle(function() use ($request, $companyId) {
            $company = Company::findOrFail($companyId);

            $locationIds = [];

            foreach ($company->locations as $location) {
                $locationIds[] = $location->id;
            }

            return ClockInLog::whereIn("location_id", $locationIds)
                ->with(['companyEmployee', 'photo', 'photo', 'location', 'clockIn', 'clockOut'])
                ->where("status", ClockInService::SUCCESS)
                ->where("type", ClockInService::CLOCKED_IN)
                ->whereNull("clock_out_id")
                ->orderBy("timestamp", "DESC")
                ->get();
        });
    }
}
