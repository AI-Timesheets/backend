<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\FacialService;
use App\Services\ObjectDetectionService;

class TimeclockController extends Controller {

    public function clockIn(Request $request) {
        return $this->handle(function() use ($request) {
          $images = $request->images;

          foreach ($images as $image) {

          }

          return [
            'test' => '1'
          ];
        });
    }

}
