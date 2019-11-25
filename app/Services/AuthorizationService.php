<?php

namespace App\Services;

use App\Http\Controllers\BackendAuth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\PayloadFactory;
use \Illuminate\Http\Request;

class AuthorizationService {

    const EXPIRATION_TIME = 3600 * 24 * 30;
    const REFRESH_REQUIREMENT_TIME = 60 * 5;

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
}
