<?php

namespace App\Http\Requests;

/**
 * Class TimesheetExport
 * @package App\Http\Requests
 *
 * @property string $startDate;
 * @property string $endDate;
 * @property int $companyId;
 * @property int $locationId;
 */
class TimesheetExportRequest extends BackendAuthorizedRequest
{
    public function rules() {
        return [
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'companyId' => 'required|integer',
            'locationId' => 'integer|nullable',
        ];
    }
}
