<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

use App\Photo;

class PhotoService {

  public static function savePhotos($images = []) : array {
    $photos = [];

    foreach ($images as $image) {
      $uuid = uniqid();
      $filename = "{$uuid}.jpg";
      Storage::disk('s3')->put($filename, base64_decode($image));
      $photos[] = Photo::create(['file_name' => $filename]);
    }

    return $photos;
  }

  public static function getPhoto($fileName) {
      return Storage::disk('s3')->temporaryUrl($fileName, now()->addMinutes(5), []);
  }
}
