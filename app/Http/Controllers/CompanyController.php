<?php

namespace App\Http\Controllers;

use App\Http\Requests\BackendAuthorizedRequest;
use App\Services\CompanyService;

class CompanyController extends Controller {
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
}
