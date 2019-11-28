<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    //use DispatchesJobs, ValidatesRequests;

    protected function success($result) {
        if ($result) {
            return response()->json([
                'success' => true,
                'result' => $result,
            ]);
        } else {
            return response()->json([
                'success' => true,
            ]);
        }
    }

    protected function failure($error) {
        return response()->json([
            'success' => false,
            'error' => $error,
        ]);
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
