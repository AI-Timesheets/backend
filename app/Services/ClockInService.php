<?php

namespace App\Services;

use App\Company;
use App\ClockInLog;
use App\Services\FacialService;

class ClockInService {
    public static function runClockIn($photos, Company $company) {
      $employees = [];

      foreach ($photos as $photo) {
        $employees[] = FacialService::scanEmployeeFace($photo, $company);
      }

      return last($employees);
    }
}
