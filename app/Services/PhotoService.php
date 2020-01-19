<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

use App\Company;
use App\Photo;
use App\CompanyEmployee;
use App\EmployeeFaces;
use App\Services\ObjectDetectionService;

use Aws\Rekognition\Exception\RekognitionException;

class PhotoService {

  public static function savePhotos($photos = []) : void {
    //
  }
}