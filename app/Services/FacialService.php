<?php

namespace App\Services;

use App\Company;
use App\Photo;
use App\User;
use App\Services\CompanyService;
use Aws\Rekognition\RekognitionClient;

class FacialService {
  private static function _getClient() {
    return new RekognitionClient([
      'region'    => 'us-east-1',
      'version'   => 'latest'
    ]);
  }

  public static function registerFace(User $user, Photo $photo) {
    $company = CompanyService::getUserCompanies($user)->first();

    $faceCollectionId = $company->aws_collection_id;

    if (! $faceCollectionId) {
      $faceCollectionId = self::createCompanyFaceCollection($company);
    }

    self::indexFace($user, $photo, $faceCollectionId);
  }

  public static function createCompanyFaceCollection(Company $company) {
    if ($company->aws_collection_id) {
      throw \Exception('Face collection already exists');
    }

    $collection_id = uniqid();

    self::_getClient()->createCollection([
      'CollectionId' => $collection_id
    ]);

    $company->aws_collection_id = $collection_id;
    $company->save();

    return $collection_id;
  }

  public static function indexFace( User $user, Photo $photo, $awsCollectionId) {
    return self::_getClient()->indexFaces([
      'CollectionId' => $awsCollectionId,
      // 'DetectionAttributes' => [],
      'ExternalImageId' => $user->id,
      'Image' => [
          'S3Object' => [
              'Bucket' => env('AWS_BUCKET'),
              'Name' => $photo->file_name
          ]
      ],
      'MaxFaces' => 1
    ]);
  }
}