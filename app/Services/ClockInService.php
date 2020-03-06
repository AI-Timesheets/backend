<?php

namespace App\Services;

use App\Company;
use App\ClockInLog;
use App\CompanyEmployee;
use App\Helpers\Functions;
use App\Photo;
use App\Services\FacialService;

class ClockInService {

    const CLOCKED_IN = "CLOCKED_IN";
    const CLOCKED_OUT = "CLOCKED_OUT";

    const PHOTO_METHOD = "PHOTO";
    const CODE_METHOD = "LOGIN_CODE";

    const PENDING = "PENDING";
    const SUCCESS = "SUCCESS";
    const FAILED = "FAILED";

    public static function getClockInLogs($startDate, $endDate, $company, $locationId = null) {
        $locationIds = [];
        if ($locationId === null) {
            foreach ($company->locations as $location) {
                $locationIds[] = $location->id;
            }
        } else {
            $locationIds = [$locationId];
        }

        $query = ClockInLog::whereIn("location_id", $locationIds)
            ->with(['clockOut', 'location', 'companyEmployee'])
            ->where('type', self::CLOCKED_IN)
            ->where('status', self::SUCCESS)
            ->where('timestamp', '>=', $startDate)
            ->where('updated_at', '<=', $endDate)
            ->orderBy('timestamp', 'ASC');

        \Log::info($query->toSql());

        return $query->get();
    }

    private static function getLastClockIn($employee):? ClockInLog{
        return ClockInLog::where("company_employee_id", $employee->id)
            ->with(["clockOut", "clockIn"])
            ->where("type", self::CLOCKED_IN)
            ->where("status", self::SUCCESS)
            ->whereNull("clock_out_id")
            ->orderBy("id", 'DESC')
            ->first();
    }

    private static function validType($type) {
        return $type === self::CLOCKED_IN || $type === self::CLOCKED_OUT;
    }

    private static function validMethod($method) {
        return $method === self::PHOTO_METHOD || $method === self::CODE_METHOD;
    }

    public static function newClockInLog(CompanyEmployee $employee, $type, $method, $latitude, $longitude, $photoId = null) {

        if (!self::validType($type)) {
            throw new \Exception("Invalid Type: ".$type);
        }

        if (!self::validMethod($method)) {
            throw new \Exception("Invalid method: ".$method);
        }

        $geographicLocation = GeolocationService::GetGeolocationByCoordinates($latitude, $longitude);

        $clockIn = new ClockInLog();
        $clockIn->location_id = $employee->location_id;
        $clockIn->company_employee_id = $employee->id;
        $clockIn->timestamp = Functions::ISOTimestamp();
        $clockIn->geographic_location_id = $geographicLocation->id;
        $clockIn->type = $type;
        $clockIn->method = $method;
        $clockIn->status = self::PENDING;

        if ($method === self::PHOTO_METHOD) $clockIn->photo_id = $photoId;

        $clockIn->save();

        return $clockIn;
    }

    public static function canClockIn(CompanyEmployee $employee) {
        $clockIn = self::getLastClockIn($employee);
        \Log::info($clockIn);
        return !$clockIn || $clockIn->clockOut !== null;
    }

    public static function canClockOut(CompanyEmployee $employee) {
        $clockIn = self::getLastClockIn($employee);
        \Log::info($clockIn);
        return $clockIn && $clockIn->clockOut === null;
    }

    public static function getEmployeeViaPhotos($photos, $company)
    {
        $employees = [];

        foreach ($photos as $photo) {
            $employees[] = FacialService::scanEmployeeFace($photo, $company);
        }

        if (!Functions::same($employees, function ($employee) {
            return $employee->id;
        })) {
            throw new \Exception("Not all employees are the same");
        }

        return last($employees);
    }

    public static function getEmployeeViaLoginCode($loginCode, $company) {
        $employee = CompanyEmployee::where("company_id", $company->id)
            ->where("login_code", $loginCode)
            ->first();

        if (!$employee) {
            throw new \Exception("Invalid Login Code");
        }

        return $employee;
    }

    private static function tryClockIn($employee, $clockIn) {
        if (!self::canClockIn($employee)) {
            $clockIn->status = self::FAILED;
            $clockIn->error = "Employee already clocked in";
        } else {
            $clockIn->status = self::SUCCESS;
        }

        $clockIn->save();

        return $clockIn;
    }

    private static function tryClockOut($employee, $clockOut) {
        if (!self::canClockOut($employee)) {
            $clockOut->status = self::FAILED;
            $clockOut->error = "Employee isn't clocked in";
            $clockOut->save();
        } else {
            $clockIn = self::getLastClockIn($employee);
            $clockOut->clock_in_id = $clockIn->id;
            $clockOut->status = self::SUCCESS;
            $clockOut->save();
            $clockIn->clock_out_id = $clockOut->id;
            $clockIn->save();
        }

        return $clockOut;
    }

    public static function getStatus($employee) {
        $clockIn = self::getLastClockIn($employee);

        if ($clockIn && !$clockIn->clockedOut) {
            return [
                'status' => self::CLOCKED_IN,
                'clockIn' => $clockIn,
                'employee' => $employee,
            ];
        } else {
            return [
                'status' => self::CLOCKED_OUT,
                'clockIn' => $clockIn,
                'employee' => $employee,
            ];
        }
    }

    public static function clockInWithPhoto($employee, $photoId, $lat, $long) {
        $clockIn = self::newClockInLog($employee, self::CLOCKED_IN, self::PHOTO_METHOD, $lat, $long, $photoId);
        return self::tryClockIn($employee, $clockIn);
    }

    public static function clockIn($employee, $lat, $long) {
        $clockIn = self::newClockInLog($employee, self::CLOCKED_IN, self::CODE_METHOD, $lat, $long);
        return self::tryClockIn($employee, $clockIn);
    }

    public static function clockOutWithPhoto($employee, $photoId, $lat, $long) {
        $clockOut = self::newClockInLog($employee, self::CLOCKED_OUT, self::PHOTO_METHOD, $lat, $long, $photoId);
        return self::tryClockOut($employee, $clockOut);
    }

    public static function clockOut($employee, $lat, $long) {
        $clockOut = self::newClockInLog($employee, self::CLOCKED_OUT, self::CODE_METHOD, $lat, $long);
        return self::tryClockOut($employee, $clockOut);
    }

}
