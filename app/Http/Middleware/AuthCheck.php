<?php

namespace App\Http\Middleware;

use App\Services\AuthorizationService;
use Closure;
use App\Company;
use Illuminate\Support\Facades\Log;

class AuthCheck
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
        Log::debug('here');
        try {
            $payload = AuthorizationService::authenticateHeader($request);
            Log::debug($payload);
            if (!$payload) {
                throw new \Exception("Failed to authorize token");
            }

            return $next($request);
        } catch (\Exception $e) {
            Log::debug($e);
            abort(403, $e->getMessage());
        }
    }
}
