<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRecoveryRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RecoverUserRequest;
use App\Http\Requests\SignupRequest;
use App\Mail\UserRecoveryEmail;
use App\Mail\UserVerificationEmail;
use App\Services\AuthorizationService;
use App\User;
use App\UserRecovery;
use App\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BackendAuth extends Controller
{
    public function self(Request $request) {
        return $this->handle(function() use ($request) {
            return $request->user;
        });
    }

    public function register(SignupRequest $request) {
        return $this->handle(function() use ($request) {
            $user = AuthorizationService::register(
                $request->firstName,
                $request->lastName,
                $request->username,
                $request->email,
                $request->password
            );

            $userVerification = AuthorizationService::createUserVerificationToken($user);

            $verificationLink = config('app.web_url')."#/verify/".$userVerification->verification_key;

            Mail::to($user->email)->send(new UserVerificationEmail($verificationLink));

            return $user;
        });
    }

    public function login(LoginRequest $request) {
        return $this->handle(function() use ($request) {
            return AuthorizationService::login($request->usernameOrEmail, $request->password);
        });
    }

    public function verify($key) {
        return $this->handle(function() use ($key) {
            $user = AuthorizationService::verifyUserKey($key);
            return $user;
        });
    }

    public function recover(RecoverUserRequest $request) {
        return $this->handle(function() use ($request) {
            $token = AuthorizationService::createUserRecoveryToken($request->email);
            $recoveryLink = config('app.web_url')."#/recover/".$token->recovery_key;
            Mail::to($request->email)->send(new UserRecoveryEmail($recoveryLink));
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
