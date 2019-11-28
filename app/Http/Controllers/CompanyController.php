<?php

namespace App\Http\Controllers;

use App\Company;
use App\CompanyEmployee;
use App\Http\Requests\BackendAuthorizedRequest;
use App\Http\Requests\CreateCompanyEmployeeRequest;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\CreateLocationRequest;
use App\Services\CompanyService;

class CompanyController extends Controller {

    private function handleIfOwner(BackendAuthorizedRequest $request, $companyId, $handleFn) {
        return $this->handle(function() use ($request, $companyId, $handleFn) {

            $company = Company::find($companyId);

            if (!$company) {
                throw new \Exception("Company does not exist");
            }

            if (!$request->user->isOwnerOf($company)) {
                throw new \Exception("User is not owner of company");
            }

            return $handleFn($company);
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
            return CompanyService::createCompanyLocation($company, $request->name);
        });
    }

    public function updateLocation(CreateLocationRequest $request, $companyId, $id) {
        return $this->handleIfOwner($request, $companyId, function() use ($request, $companyId, $id) {
            $location = CompanyService::getCompanyLocation($companyId, $id);
            return CompanyService::updateCompanyLocation($location, $request->name);
        });
    }

    public function deleteLocation(BackendAuthorizedRequest $request, $companyId, $id) {
        return $this->handleIfOwner($request, $companyId, function() use ($request, $companyId, $id) {
            $location = CompanyService::getCompanyLocation($companyId, $id);
            $location->delete();
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
}
