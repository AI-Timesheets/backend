<?php

namespace App\Http\Controllers;

use App\Http\Requests\BackendAuthorizedRequest;
use App\Services\PhotoService;

class PhotoController extends Controller {
    public function getPhoto(BackendAuthorizedRequest $request, $fileName) {
        return $this->handle(function() use ($request, $fileName) {
            return PhotoService::getPhoto($fileName);
        });
    }
}
