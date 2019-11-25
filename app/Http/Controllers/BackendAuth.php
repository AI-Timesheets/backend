<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Services\AuthorizationService;
use Illuminate\Http\Request;

class BackendAuth extends Controller
{
    public function self(Request $request) {
        return $this->handle(function() use ($request) {
            return $request->user;
        });
    }

    public function register(SignupRequest $request) {
        return $this->handle(function() use ($request) {
            return AuthorizationService::register(
                $request->firstName,
                $request->lastName,
                $request->username,
                $request->email,
                $request->password
            );
        });
    }

    public function login(LoginRequest $request) {
        return $this->handle(function() use ($request) {
            return AuthorizationService::login($request->usernameOrEmail, $request->password);
        });
    }
}
