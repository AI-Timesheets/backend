<?php

namespace App\Http\Controllers;

use App\Photo;
use App\CompanyEmployee;

use App\Http\Requests\ClockInRequest;
use App\Services\ClockInService;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\FacialService;
use App\Services\ObjectDetectionService;
use App\Services\PhotoService;

class TimeclockController extends Controller {

    public function clockIn(Request $request) {
        return $this->handle(function() use ($request) {
          $images = $request->photos;
          $photos = [];

          // TODO: get current time

          $photos = PhotoService::savePhotos($images);

          foreach ($photos as $photo) {
            if (ObjectDetectionService::photoContainsDevices($photo)) {
              throw new \Exception('Device detected');
            }
          }

          $employee = ClockInService::runClockIn($photos, $request->company);

          return $employee->refresh();
        });
    }

    public function clockOut(Request $request) {
        return $this->handle(function() use ($request) {
            $images = $request->photos;
            $photos = [];

            // TODO: get current time

            $photos = PhotoService::savePhotos($images);

            foreach ($photos as $photo) {
                if (ObjectDetectionService::photoContainsDevices($photo)) {
                    throw new \Exception('Device detected');
                }
            }

            $employee = ClockInService::runClockOut($photos, $request->company);

            return $employee->refresh();
        });
    }

}
