<?php

use Illuminate\Http\Request;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("backend-auth")->group(function() {
    Route::get("self", "BackendAuth@self")->middleware("auth.backend");
    Route::post("register", "BackendAuth@register");
    Route::post("login", "BackendAuth@login");
    Route::post("recover", "BackendAuth@recover");
    Route::post("change-password", "BackendAuth@changePassword")->middleware("auth.backend");
    Route::post("change-password-recovery", "BackendAuth@changePasswordRecovery");
});
