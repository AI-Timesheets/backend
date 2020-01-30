<?php

namespace App;

use App\Services\PhotoService;

/**
 * Class Photo
 * @package App
 *
 * @property string $file_name;
 * @property string $face_id;
 */
class Photo extends BaseModel {
    protected $fillable = [
        'file_name',
        'face_id'
    ];

    protected $appends = [
        'url',
    ];

    public function getUrlAttribute() {
        return PhotoService::getPhoto($this->file_name);
    }
}
