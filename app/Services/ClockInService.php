<?php

namespace App\Services;

use App\Company;
use App\ClockInLog;
use App\CompanyEmployee;
use App\Helpers\Functions;
use App\Photo;
use App\Services\FacialService;

class ClockInService {

    private static function getLastClockIn($employee):? ClockInLog{
        return ClockInLog::where("company_employee_id", $employee->id)->orderBy("id", 'DESC')->first();
    }

    private static function clockIn(CompanyEmployee $employee, Photo $photo) {
        $clockIn = self::getLastClockIn($employee);

        if ($clockIn && $clockIn->clockout_timestamp === null) {
            throw new \Exception("Employee is already clocked in");
        }

        $clockIn = new ClockInLog();
        $clockIn->location_id = $employee->location_id;
        $clockIn->company_employee_id = $employee->id;
        $clockIn->clockin_timestamp = Functions::timestamp();
        $clockIn->clock_in_photo_id = $photo->id;
        $clockIn->save();
    }

    private static function clockOut(CompanyEmployee $employee, Photo $photo) {
        $clockIn = self::getLastClockIn($employee);

        if (!$clockIn || $clockIn->clockout_timestamp !== null) {
            throw new \Exception("Employee is not clocked in");
        }

        $clockIn->clockout_timestamp = Functions::timestamp();
        $clockIn->clock_out_photo_id = $photo->id;
        $clockIn->save();
    }

    public static function getEmployee($photos, $company)
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

    public static function runClockIn($photos, Company $company) {
        $employee = self::getEmployee($photos, $company);
        self::clockIn($employee, $photos[0]);
    }

    public static function runClockOut($photos, Company $company) {
        $employee = self::getEmployee($photos, $company);
        self::clockOut($employee, $photos[0]);
    }
}
