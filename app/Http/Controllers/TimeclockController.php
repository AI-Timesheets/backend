<?php

namespace App\Http\Controllers;

use App\Photo;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ClockInRequest;

use App\Services\FacialService;
use App\Services\ObjectDetectionService;

class TimeclockController extends Controller {

    public function clockIn(ClockInRequest $request) {
        return $this->handle(function() use ($request) {
          $images = $request->images;
          $photos = [];

          foreach ($images as $image) {
            $uuid = uniqid();
            Storage::disk('s3')->put($uuid, $image);
            $photos[] = Photo::create(['file_name' => $uuid]);
          }

          foreach ($photos as $photo) {
            if (ObjectDetectionService::photoContainsDevices($photo)) {
              throw new \Exception('Device detected');
            }
          }

          $employees = [];
          foreach ($photos as $photo) {
            $employees[] = FacialService::scanEmployeeFace($photo, $request->company);
          }

          return last($employees);
        });
    }

    public function status() {
      return response()->json([
        'name' => 'Abigail',
        'state' => 'CA'
      ]);
    }
}
