<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRecoveryRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RecoverUserRequest;
use App\Http\Requests\SignupRequest;
use App\Services\AuthorizationService;
use App\User;
use App\UserRecovery;
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

    public function recover(RecoverUserRequest $request) {
        return $this->handle(function() use ($request) {
            return ['recovery_key' => AuthorizationService::createUserRecoveryToken($request->email)];
        });
    }

    public function changePassword(ChangePasswordRequest $request) {
        return $this->handle(function() use ($request) {
           return ['user' => AuthorizationService::changeUserPassword($request->user, $request->password)];
        });
    }

    public function changePasswordRecovery(ChangePasswordRecoveryRequest $request) {
        return $this->handle(function() use ($request) {
           $user = AuthorizationService::validateUserRecoveryKey($request->recoveryKey);
           $user = AuthorizationService::changeUserPassword($user, $request->password);
           UserRecovery::where("recovery_key", $request->recoveryKey)->delete();
           return ['user' => $user];
        });
    }
}
