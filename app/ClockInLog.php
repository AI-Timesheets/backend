<?php

namespace App;

/**
 * Class ClockInLog
 * @package App
 *
 * @property string $clockin_timestamp;
 * @property string $clockout_timestamp;
 * @property int $company_employee_id;
 * @property int $photo_id;
 * @property int $location_id;
 * @property \App\CompanyEmployee $companyEmployee;
 * @property \App\Photo $clock_in_photo;
 * @property \App\Photo $clock_out_photo;
 * @property \App\Location $location;
 */
class ClockInLog extends BaseModel {
    protected $fillable = [
        'clockin_timestamp',
        'clockout_timestamp',
        'company_employee_id',
        'clock_in_photo_id',
        'clock_out_photo_id',
        'location_id',
    ];

    public function companyEmployee() {
        return $this->belongsTo("\App\CompanyEmployee");
    }

    public function clock_in_photo() {
        return $this->belongsTo("\App\Photo");
    }

    public function clock_out_photo() {
        return $this->belongsTo("\App\Photo");
    }

    public function location() {
        return $this->belongsTo("\App\Location");
    }
}
