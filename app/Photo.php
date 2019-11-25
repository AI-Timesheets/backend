<?php

namespace App;

/**
 * Class Photo
 * @package App
 *
 * @property string $file_name;
 */
class Photo extends BaseModel {
    protected $fillable = [
        'file_name',
    ];
}
