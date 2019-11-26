<?php

namespace App\Http\Requests;

/**
 * Class CreateLocationRequest
 * @package App\Http\Requests
 *
 * @property string $name;
 * @property int $companyId;
 */
class CreateLocationRequest extends BackendAuthorizedRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'companyId' => 'required|integer',
        ];
    }
}
