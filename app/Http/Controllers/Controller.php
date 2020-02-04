<?php

namespace App\Http\Controllers;

use App\Company;
use App\Helpers\Timer;
use App\Http\Requests\BackendAuthorizedRequest;
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

    protected function handleIfOwner(BackendAuthorizedRequest $request, $companyId, $handleFn) {
        return $this->handle(function() use ($request, $companyId, $handleFn) {

            $company = Company::where("id", $companyId)->with(['locations'])->first();

            if (!$company) {
                throw new \Exception("Company does not exist");
            }

            if (!$request->user->isOwnerOf($company)) {
                throw new \Exception("User is not owner of company");
            }

            return $handleFn($company);
        });
    }
}
