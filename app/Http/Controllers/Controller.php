<?php

namespace App\Http\Controllers;

use App\Helpers\Timer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    //use DispatchesJobs, ValidatesRequests;

    protected function success($result, $statusCode = 200) {
        if ($result) {
            return response()->json([
                'success' => true,
                'result' => $result,
            ], $statusCode);
        } else {
            return response()->json([
                'success' => true,
            ], $statusCode);
        }
    }

    protected function failure($error, $errorCode = 400) {
        return response()->json([
            'success' => false,
            'error' => $error,
        ], $errorCode);
    }

    protected function handle($func) {
        try {
            $funcReturn = $func();
            return $this->success($funcReturn);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->failure($e->getMessage());
        }
    }
}
