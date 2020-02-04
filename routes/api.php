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
    Route::post("verify/{key}", "BackendAuth@verify");
    Route::post("recover", "BackendAuth@recover");
    Route::post("change-password", "BackendAuth@changePassword")->middleware("auth.backend");
    Route::post("change-password-recovery", "BackendAuth@changePasswordRecovery");
});

Route::prefix("company")->group(function() {
    Route::post("initial", "CompanyController@createInitialCompany")->middleware("auth.backend");
    Route::get("initial", "CompanyController@hasInitialCompany")->middleware("auth.backend");

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

    Route::get("/{id}/employee", "CompanyController@employees")->middleware("auth.backend");
    Route::post("/{id}/employee", "CompanyController@createEmployee")->middleware("auth.backend");
    Route::get("/{id}/employee/{employeeId}", "CompanyController@employee")->middleware("auth.backend");
    Route::put("/{id}/employee/{employeeId}", "CompanyController@updateEmployee")->middleware("auth.backend");
    Route::delete("/{id}/employee/{employeeId}", "CompanyController@deleteEmployee")->middleware("auth.backend");

    Route::get("/{id}/timeclock-logs", "CompanyController@timeclockLogs")->middleware("auth.backend");
    Route::get("/{id}/clocked-in-employees", "CompanyController@clockedInEmployees")->middleware("auth.backend");
});

Route::prefix("photo")->group(function() {
    Route::get("/{fileName}", "PhotoController@getPhoto")->middleware("auth.backend");
});

Route::prefix("mobile-auth")->group(function() {
    Route::post("login", "MobileAuth@mobileLogin");
    Route::get("self", "MobileAuth@self")->middleware("auth.mobile");
});

Route::prefix("time-clock")->group(function() {
    Route::post("recognize", "TimeclockController@recognize")->middleware("auth.mobile");
    Route::post("status", "TimeclockController@status")->middleware("auth.mobile");
    Route::post("clock-in", "TimeclockController@clockIn")->middleware("auth.mobile");
    Route::post("clock-out", "TimeclockController@clockOut")->middleware("auth.mobile");
});

Route::prefix("export")->group(function() {
    Route::post("timesheet", "ExportController@exportTimesheet")->middleware("auth.backend");
});
