<?php

namespace App\Http\Controllers;

use App\Http\Requests\BackendAuthorizedRequest;
use App\Http\Requests\MobileAuthorizedRequest;
use App\Http\Requests\RecognizeRequest;
use App\Photo;
use App\CompanyEmployee;

use App\Http\Requests\ClockInRequest;
use App\Services\ClockInService;
use App\Services\CompanyService;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\FacialService;
use App\Services\ObjectDetectionService;
use App\Services\PhotoService;

class TimeclockController extends Controller {

    public function recognize(MobileAuthorizedRequest $request) {
        return $this->handle(function() use ($request) {
            $images = $request->photos;
            $photos = PhotoService::savePhotos($images);

            foreach ($photos as $photo) {
                if (ObjectDetectionService::photoContainsDevices($photo)) {
                    throw new \Exception('Device detected');
                }
            };
            $employee = ClockInService::getEmployeeViaPhotos($photos, $request->company);

            return ['employee' => $employee, 'photos' => $photos];
        });
    }

    public function status(ClockInRequest $request) {
        return $this->handle(function() use ($request) {
            $employee = ClockInService::getEmployeeViaLoginCode($request->loginCode, $request->company);

            return ClockInService::getStatus($employee);
        });
    }

    public function clockIn(ClockInRequest $request) {
        return $this->handle(function() use ($request) {
            $employee = ClockInService::getEmployeeViaLoginCode($request->loginCode, $request->company);

            if ($request->photoId) {
                return ClockInService::clockInWithPhoto($employee, $request->photoId);
            } else {
                return ClockInService::clockIn($employee);
            }
        });
    }

    public function clockOut(ClockInRequest $request) {
        return $this->handle(function() use ($request) {
            $employee = ClockInService::getEmployeeViaLoginCode($request->loginCode, $request->company);

            if ($request->photoId) {
                return ClockInService::clockOutWithPhoto($employee, $request->photoId);
            } else {
                return ClockInService::clockOut($employee);
            }
        });
    }

}
