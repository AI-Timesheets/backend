<?php

namespace App\Http\Requests;

/**
 * Class CreateCompanyRequest
 * @package App\Http\Requests
 *
 * @property int $companyId;
 * @property string $loginCode;
 * @property string $photoId;
 * @property float $latitude;
 * @property float $longitude;
 */
class ClockInStatusRequest extends MobileAuthorizedRequest
{
    public function rules()
    {
        return [
            'loginCode' => 'required',
            'photoId' => 'nullable',
        ];
    }
}
