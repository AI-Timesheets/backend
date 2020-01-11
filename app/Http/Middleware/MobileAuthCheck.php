<?php

namespace App\Http\Middleware;

use App\Services\AuthorizationService;
use Closure;
use App\Company;

class MobileAuthCheck
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
                throw new \Exception("Failed to authorize token");
            }

            $company = \Company::where("company_code", $payload->toArray()[0]->company->company_code);

            if (!$company) {
                throw new \Exception("Company does not exist");
            }

            $request->merge(['company' => $company]);


            return $next($request);
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
    }
}
