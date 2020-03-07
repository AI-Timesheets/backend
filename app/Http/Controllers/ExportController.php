<?php


namespace App\Http\Controllers;


use App\Exports\TimesheetExport;
use App\Http\Requests\TimesheetExportRequest;
use App\Services\ClockInService;
use App\Services\CompanyService;
use App\Services\ExportService;
use Maatwebsite\Excel\Excel;

class ExportController extends Controller
{
    public function exportTimesheetToExcel(TimesheetExportRequest $request) {

        $company = CompanyService::getCompany($request->companyId);

        if ($company->owner_user_id !== $request->user->id) {
            abort(403);
        }

        $timesheetExport = ExportService::getTimesheetByDays($request->startDate, $request->endDate, $company, $request->locationId);

        return \Excel::download(new TimesheetExport($timesheetExport), 'timesheet.xlsx');
    }

    public function exportTimesheet(TimesheetExportRequest $request) {
        $company = CompanyService::getCompany($request->companyId);

        if ($company->owner_user_id !== $request->user->id) {
            abort(403);
        }

        return $this->success(ExportService::getTimesheetByDays($request->startDate, $request->endDate, $company, $request->locationId));
    }
}
