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

Route::prefix("company")->group(function() {
    Route::get("", "CompanyController@companies")->middleware("auth.backend");
    Route::post("", "CompanyController@createCompany")->middleware("auth.backend");
    Route::get("/{id}", "CompanyController@company")->middleware("auth.backend");
    Route::put("/{id}", "CompanyController@updateCompany")->middleware("auth.backend");
    Route::delete("/{id}", "CompanyController@deleteCompany")->middleware("auth.backend");

    Route::get("/{id}/location", "CompanyController@locations")->middleware("auth.backend");
    Route::post("/{id}/location", "CompanyController@createLocation")->middleware("auth.backend");
    Route::get("/{id}/location/{locationId}", "CompanyController@location")->middleware("auth.backend");
    Route::put("/{id}/location/{locationId}", "CompanyController@updateLocation")->middleware("auth.backend");
    Route::delete("/{id}/location/{locationId}", "CompanyController@deleteLocation")->middleware("auth.backend");
});
