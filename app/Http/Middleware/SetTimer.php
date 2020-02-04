<?php


namespace App\Http\Middleware;

use App\Helpers\Timer;
use Closure;

class SetTimerMiddleware
{
    public function handle($request, Closure $next) {

        $now = Timer::now();

        $request->merge(['startTime' => $now]);

        return $next($request);
    }
}

