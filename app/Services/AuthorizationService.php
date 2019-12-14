<?php

namespace App\Services;

use App\CompanyEmployee;
use App\Helpers\Random;
use App\Http\Controllers\BackendAuth;
use App\Repositories\UserRepository;
use App\User;
use App\UserRecovery;
use App\UserVerification;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\PayloadFactory;
use \Illuminate\Http\Request;

class AuthorizationService {

    // A month
    const EXPIRATION_TIME = 3600 * 24 * 30;

    // An hour
    const RECOVERY_EXPIRATION_TIME = 60 * 30;

    private static function getJWT($payload, $uid): string {
        $factory = JWTFactory::customClaims([
            $payload,
            'sub' => $uid,
            'iat' => time(),
            'exp' => time() + self::EXPIRATION_TIME,
            'nbf' => time(),
            'iss' => env("APP_URL", "http://localhost:8000"),
            'jti' => Hash::make(time().".".$uid),
            'aud' => env("APP_URL"),
        ]);
        $payload = $factory->make();
        $token = JWTAuth::encode($payload);
        return $token;
    }

    private static function validateJWT($jwt) {
        try {
            $token = JWTAuth::setToken($jwt)->getToken();
            $payload = JWTAuth::decode($token);
            return $payload;
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            throw new \Exception("Token Expired");

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            throw new \Exception("Token Invalid");

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            throw new \Exception("Token Missing");

        }

    }

    public static function authenticateHeader(Request $request) {
        if (!$auth = $request->header("Authorization")) {
            abort(403, "Access Denied");
        }

        $authParts = explode(" ", $auth);

        if (count($authParts) !== 2 || $authParts[0] !== "Bearer") {
            abort(403, "Access Denied: Misformed Authorization Header");
        }

        $token = $authParts[1];

        try {

            $payload = AuthorizationService::validateJWT($token);
            return $payload;

        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
    }

    public static function login($usernameOrEmail, $password) {

        /** @var User $user */
        $user = User::where("username", $usernameOrEmail)
            ->orWhere("email", $usernameOrEmail)
            ->first();

        if (!$user) {
            throw new \Exception("Invalid Login Credentials");
        }

        if (!Hash::check($password, $user->password)) {
            throw new \Exception("Invalid Login Credentials");
        }

        if (!$user->verified) {
            throw new \Exception("Your account has not been verified yet. Please check your email.");
        }

        return ['user' => $user, 'jwt' => AuthorizationService::getJWT(['user' => $user], $user->id)];
    }

    public static function register($firstName, $lastName, $username, $email, $password) {

        if (User::where("username", $username)->exists()) {
            throw new \Exception("Username already registered");
        }

        if (User::where("email", $email)->exists()) {
            throw new \Exception("Email already registered");
        }

        $user = new User();

        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->username = $username;
        \Log::info($password);
        $user->password = Hash::make($password);

        $user->save();

        \Log::info($user->password);

        return $user;
    }

    public static function createUserVerificationToken(User $user) {
        $verification = new UserVerification();

        $verification->user_id = $user->id;
        $verification->verification_key = Random::stringWhereNot(32, function($key) {
            UserVerification::where("verification_key", $key)->exists();
        });
        $verification->save();

        return $verification;
    }

    public static function verifyUserKey($key) {
        if (!$verification = UserVerification::where("verification_key", $key)->whereNull('verified_at')->first()) {
            throw new \Exception("Invalid verification key.");
        }

        $verification->user->verified = true;
        $verification->verified_at = date("Y-m-d H:i:s", time());
        $verification->user->save();
        $verification->save();

        return ['user' => $verification->user, 'jwt' => AuthorizationService::getJWT(['user' => $verification->user], $verification->user->id)];
    }

    public static function createUserRecoveryToken($email) {
        if (!$user = User::where("email", $email)->first()) {
            throw new \Exception("User does not exist");
        }

        $userRecovery = new UserRecovery();

        $userRecovery->user_id = $user->id;
        $userRecovery->expires_at = date("Y-m-d H:i:s", time() + self::RECOVERY_EXPIRATION_TIME);
        $userRecovery->recovery_key = Random::stringWhereNot(32, function($key) { UserRecovery::where("recovery_key",$key)->exists();});

        $userRecovery->save();

        return $userRecovery;
    }

    public static function validateUserRecoveryKey($key): User {
        \Log::info(date("Y-m-d H:i:s", time()));
        if (!$recovery = UserRecovery::where("recovery_key", $key)->where("expires_at", ">", date("Y-m-d H:i:s", time()))->first()) {
            throw new \Exception("Invalid recovery key");
        }

        return $recovery->user;
    }

    public static function changeUserPassword(User $user, $newPassword): User {
        $user->password = Hash::make($newPassword);
        $user->save();
        return $user;
    }
}
