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
    $company = $employee->company;

    $awsCollectionId = $company->aws_collection_id;

    if (! $awsCollectionId) {
      $awsCollectionId = self::createCompanyFaceCollection($company);
    }

    // See if face exists already
    foreach ($photos as $photo) {
      try {
        self::scanEmployeeFace($photo, $company);
      } catch (\Exception $e) {
        throw new \Exception('Face already registered');
      }
    }
  }
}