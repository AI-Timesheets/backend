<?php

namespace App\Services;

use Aws\Rekognition\RekognitionClient;

class RekognitionService {
  const AWS_FACE_RECORDS_KEY = 'FaceRecords';
  const AWS_FACE_MATCHES_KEY = 'FaceMatches';
  const AWS_FACE_KEY = 'Face';
  const AWS_FACE_ID_KEY = 'FaceId';
  const AWS_UNINDEXED_FACES_KEY = 'UnindexedFaces';
  const AWS_LABELS_KEY = 'Labels';
  const AWS_CONFIDENCE_KEY = 'Confidence';
  const AWS_PARENTS_KEY = 'Parents';
  const AWS_MAX_FACES_VALUE = 'EXCEEDS_MAX_FACES';

  public static function rekognition() {
    return new RekognitionClient([
      'region'    => 'us-east-2',
      'version'   => 'latest'
    ]);
  }
}