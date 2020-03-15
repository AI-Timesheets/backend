<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;


class Cors {
  public function handle($request, Closure $next)
  {
    return $next($request)
      ->header('Access-Control-Allow-Origin', env('CORS_DOMAINS', 'https://app.aitimesheets.com'))
      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
      ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
  }
}