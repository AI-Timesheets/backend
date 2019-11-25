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
        $payload = AuthorizationService::authenticateHeader($request);

        $user = User::where("id", $payload["user"]->id)->first();

        $request->merge(['user' => $user]);

        $request->setUserResolver(function() use ($user) {
            return $user;
        });

        return $next($request);
    }
}
