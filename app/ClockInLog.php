<?php

namespace App;

use App\Services\ClockInService;

/**
 * Class ClockInLog
 * @package App
 *
 * @property string $timestamp;
 * @property int $company_employee_id;
 * @property int $photo_id;
 * @property int $location_id;
 * @property string $type;
 * @property int $clock_in_id;
 * @property string $method;
 * @property string $status;
 * @property string $error;
 * @property \App\CompanyEmployee $companyEmployee;
 * @property \App\Photo $photo;
 * @property \App\Location $location;
 * @property \App\ClockInLog $clockIn;
 * @property \App\ClockInLog $clockOut;
 */
class ClockInLog extends BaseModel {
    protected $fillable = [
        'timestamp',
        'company_employee_id',
        'photo_id',
        'location_id',
        'type',
        'clock_in_id',
        'clock_out_id',
        'latitude',
        'longitude',
        'method',
        'status',
        'error',
    ];

    public function companyEmployee() {
        return $this->belongsTo("\App\CompanyEmployee");
    }

    public function photo() {
        return $this->belongsTo("\App\Photo");
    }

    public function location() {
        return $this->belongsTo("\App\Location");
    }

    public function clockIn() {
        return $this->hasOne("\App\ClockInLog", "clock_out_id");
    }

    public function clockOut() {
        return $this->hasOne("\App\ClockInLog", "clock_in_id");
    }
}
