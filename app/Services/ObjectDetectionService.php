<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

use App\Photo;

use Aws\Rekognition\Exception\RekognitionException;

class ObjectDetectionService extends RekognitionService {
  const BAD_LABEL_CONFIDENCE_THRESHOLDS = [
    'Electronics' => 95,
    'Screen' => 95
  ];

  public static function photoContainsDevices(Photo $photo) : bool {
    $response = self::detectLabels($photo);
    $labels = $response[self::AWS_LABELS_KEY];

    foreach ($labels as $label) {
      $confidence = $label[self::AWS_CONFIDENCE_KEY];
      $parents = Arr::flatten($label[self::AWS_PARENTS_KEY]);

      foreach (self::BAD_LABEL_CONFIDENCE_THRESHOLDS as $label => $threshold) {
        if (in_array($label, $parents)) {
          if ($confidence >= $threshold) {
            return true;
          }
        }
      }
    }

    return false;
  }

  public static function detectLabels(Photo $photo) {
    try {
      $response = self::rekognition()->detectLabels([
        'Image' => [
          'S3Object' => [
            'Bucket' => env('AWS_BUCKET'),
            'Name' => "{$photo->file_name}"
          ]
        ],
      ]);
    } catch (RekognitionException $e) {
      // Handle exception if needed
      throw $e;
    }

    return $response;
  }
}