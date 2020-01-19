<?php

namespace App\Http\Controllers;

use App\Photo;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ClockInRequest;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\FacialService;
use App\Services\ObjectDetectionService;

class TimeclockController extends Controller {

    public function clockIn(Request $request) {
        return $this->handle(function() use ($request) {
          $images = $request->photos;
          $photos = [];

          // TODO: get current time

          foreach ($images as $image) {
            // TODO: move this into PhotoService
            $uuid = uniqid();
            $filename = "{$uuid}.jpg";
            Storage::disk('s3')->put($filename, base64_decode($image));
            $photos[] = Photo::create(['file_name' => $filename]);
          }

          foreach ($photos as $photo) {
            if (ObjectDetectionService::photoContainsDevices($photo)) {
              throw new \Exception('Device detected');
            }
          }

          Log::info('made it past object detection');

          $employees = [];
          foreach ($photos as $photo) {
            $employees[] = FacialService::scanEmployeeFace($photo, $request->company);
          }

          // TODO: create clock in log

          return last($employees);
        });
    }

}
