<?php

namespace App;

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
}
