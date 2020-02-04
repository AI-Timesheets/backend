<?php


namespace App\Services;


use App\Helpers\Functions;

class ExportService
{
    public static function getTimesheetByDays($startDate, $endDate, $company, $locationId) {
        $logs = ClockInService::getClockInLogs($startDate, $endDate, $company, $locationId);
        $employees = $locationId ? CompanyService::getCompanyLocationEmployees($company->id, $locationId) :
            CompanyService::getCompanyEmployees($company->id);
        $dates = Functions::dateRange($startDate, $endDate);

        \Log::info($dates);

        $data = [];
        $data['names'] = [];
        foreach ($employees as $employee) {

            $data['ts'][$employee->id] = [];
            $data['dates'] = $dates;
            $data['names'][] = $employee->first_name.' '.$employee->last_name;
            $data['ts'][$employee->id] = [
                'logs' => [],
                'dates' => [],
                'employeeName' => $employee->first_name.' '.$employee->last_name,
                'startTime' => null,
                'stopTime' => null,
                'breakTime' => 0,
                'wage' => $employee->hourly_wage,
                'duration' => 0,
            ];

            foreach ($dates as $date) {
                $data['ts'][$employee->id]['dates'][$date] = 0;
            }
        }

        foreach ($logs as $log) {
            $date = Functions::toTimestamp($log->timestamp, Functions::DATESTAMP_FMT);
            $duration = Functions::deltaTime($log->timestamp, $log->clockOut->timestamp);
            $data['ts'][$log->companyEmployee->id]['logs'][] = $log;
            $data['ts'][$log->companyEmployee->id]['dates'][$date] += $duration;
            $data['ts'][$log->companyEmployee->id]['duration'] += $duration;
        }

        return $data;
    }
}
