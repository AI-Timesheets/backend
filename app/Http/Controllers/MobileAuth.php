<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\MobileLoginRequest;
use App\Services\AuthorizationService;
use Illuminate\Http\Request;

class MobileAuth extends Controller
{
    public function self(Request $request) {
        return $this->handle(function() use ($request) {
            return $request->company;
        });
    }

    public function mobileLogin(MobileLoginRequest $request) {
        return $this->handle(function() use ($request) {
            return AuthorizationService::mobileLogin($request->companyCode);
        });
    }
}
