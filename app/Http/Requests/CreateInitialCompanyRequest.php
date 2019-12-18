<?php

namespace App\Http\Requests;

/**
 * Class CreateInitialCompanyRequest
 * @package App\Http\Requests
 *
 * @property string $companyName;
 * @property string $locationName;
 */
class CreateInitialCompanyRequest extends BackendAuthorizedRequest {
    public function authorize()
    {
        return true;
    }

    public function rules() {
        return [
            'companyName' => 'required',
            'locationName' => 'required',
        ];
    }
}
