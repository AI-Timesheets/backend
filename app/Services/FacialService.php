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

class FacialService extends RekognitionService {
  const FACE_MATCH_THRESHOLD = 85;

  /**
   * Register a face.
   * 1. First create an AWS Face Collection if one does not already exist
   * 2. Check if any faces in $photos have already been registered
   * 3. Index faces
   * 3a. Exception if multiple face detected in photo
   * 3b. Exception if object detection finds devices
   * 4. Save face ID for $employee
   *
   * @param App\CompanyEmployee $employee
   * @param App\Photo[] $photos
   */
  public static function registerFace(CompanyEmployee $employee, $photos = []) : void {
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

    foreach ($photos as $photo) {
      try {
        $response = self::indexFace($photo, $awsCollectionId);
      } catch (RekognitionException $e) {
        // Handle exception
        throw $e;
      }

      $faceId = Arr::first(
        $response[self::AWS_FACE_RECORDS_KEY]
      )[self::AWS_FACE_KEY][self::AWS_FACE_ID_KEY];

      // Throw exception if multiple faces in the image
      if (
        count($response['FaceRecords']) !== 1 ||
        in_array(
          self::AWS_MAX_FACES_VALUE,
          Arr::flatten($response[self::AWS_UNINDEXED_FACES_KEY])
        )
      ) {
        self::deleteFace($faceId, $awsCollectionId);
        throw new \Exception('Multiple faces detected. Image should only contain one face');
      }

      // Object detection test
      if (ObjectDetectionService::photoContainsDevices($photo)) {
        // TODO: Send buddy punch alert
        throw new \Exception('Photo contains devices.');
      }

      // All good, register this face ID for this employee
      EmployeeFaces::create([
        'company_employee_id' => $employee->id,
        'face_id' => $faceId
      ]);
    }
  }

  /**
   * Scan employee face.
   * 1. Search AWS face collection for this photo
   * 2. Gather the face IDs detected
   * 2a. Exception if multiple employees detected
   * 2b. Exception if face is not found (this means the employee has not yet
   *  registered)
   * 3. Return recognized employee
   *
   * @param App\Photo $photo
   * @param App\Company $company
   */
  public static function scanEmployeeFace(Photo $photo, Company $company) : CompanyEmployee {
    if (! $company->aws_collection_id) {
      throw new \Exception('AWS Collection ID required for collection search');
    }

    $facial = self::rekognition()->searchFacesByImage([
      'CollectionId' => "{$company->aws_collection_id}",
      'FaceMatchThreshold' => self::FACE_MATCH_THRESHOLD,
      'Image' => [
        'S3Object' => [
          'Bucket' => env('AWS_BUCKET'),
          'Name' => "{$photo->file_name}"
        ]
      ]
    ]);

    $faceIds = [];
    foreach ($facial[self::AWS_FACE_MATCHES_KEY] as $faceMatch) {
      $faceIds[] = $faceMatch[self::AWS_FACE_KEY][self::AWS_FACE_ID_KEY];
    }

    $employees = EmployeeFaces::whereIn('face_id', $faceIds)
      ->groupBy('company_employee_id');

    if ($employees->count() !== 1) {
      throw new \Exception('Multiple employees detected');
    }

    if (! $employees->first()) {
      throw new \Exception('Face does not exist');
    }

    return $employees->first()->companyEmployee;
  }

  public static function createCompanyFaceCollection(Company $company) : string {
    if ($company->aws_collection_id) {
      throw new \Exception('Face collection already exists');
    }

    $collection_id = uniqid();

    try {
      self::rekognition()->createCollection([
        'CollectionId' => "{$collection_id}"
      ]);
    } catch (RekognitionException $e) {
      // Handle exception if needed
      throw $e;
    }

    $company->aws_collection_id = $collection_id;
    $company->save();

    return $collection_id;
  }

  public static function deleteEmployeeFaces(CompanyEmployee $employee) : void {
    $awsCollectionId = $employee->company->aws_collection_id;

    foreach (
      EmployeeFaces::where('company_employee_id', $employee->id)->all()
        as $employeeFace
      ) {
      self::deleteFace($employeeFace->face_id, $awsCollectionId);
      $employeeFace->delete();
    }
  }

  public static function indexFace(Photo $photo, $awsCollectionId) {
    return self::rekognition()->indexFaces([
      'CollectionId' => "{$awsCollectionId}",
      'Image' => [
          'S3Object' => [
              'Bucket' => env('AWS_BUCKET'),
              'Name' => "{$photo->file_name}"
          ]
      ],
      'MaxFaces' => 1
    ]);
  }

  public static function deleteFaces($faceIds = [], $awsCollectionId) {
    return self::rekognition()->deleteFaces([
      'CollectionId' => "{$awsCollectionId}",
      'FaceIds' => $faceIds
    ]);
  }

  public static function deleteFace($faceId, $awsCollectionId) {
    return self::deleteFaces([$faceId]);
  }
}