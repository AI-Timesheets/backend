<?php

namespace App\Http\Middleware;

use App\Services\AuthorizationService;
use Closure;
use App\User;

class BackendAuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $payload = AuthorizationService::authenticateHeader($request);

            if (!$payload) {
                throw new Exception("Failed to authorize token");
            }

            $user = User::where("id", $payload->toArray()[0]->user->id)->where('verified', true)->first();

            if (!$user) {
                throw new \Exception("User does not exist or is not verified");
            }

            $request->merge(['user' => $user]);

            $request->setUserResolver(function () use ($user) {
                return $user;
            });

            return $next($request);
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
    }
}
