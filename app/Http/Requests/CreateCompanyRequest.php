<?php

namespace App\Http\Requests;

/**
 * Class CreateCompanyRequest
 * @package App\Http\Requests
 *
 * @property string $name;
 */
class CreateCompanyRequest extends BackendAuthorizedRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }
}
