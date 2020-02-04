<?php


namespace App\Http\Middleware;


use App\Helpers\Timer;
use Closure;

class AppendDurationMiddleware
{
    public function handle($request, Closure $next) {
        $response = $next($request);
        $stop = Timer::now();

        $data = $response->getData();

        $data->startTime = $request->startTime;
        $data->stopTime = $stop;
        $data->duration = $stop - $request->startTime;

        $response->setData($data);

        return $response;
    }
}
