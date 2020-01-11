<?php

namespace App\Http\Requests;

/**
 * Class CreateCompanyRequest
 * @package App\Http\Requests
 *
 * @property string $name;
 */
class ClockInRequest extends BackendAuthorizedRequest
{
    public function rules()
    {
        return [
            'photos.*' => 'required|image',
        ];
    }
}
