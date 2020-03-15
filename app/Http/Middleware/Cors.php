<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;


class Cors {
  public function handle($request, Closure $next)
  {
    Log::debug('cors');
    return $next($request)
      ->header('Access-Control-Allow-Origin', 'https://app.aitimesheets.com')
      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
      ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
  }
}