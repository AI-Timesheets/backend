<?php

namespace App\Http\Requests;

/**
 * Class CreateLocationRequest
 * @package App\Http\Requests
 *
 * @property string $name;
 * @property int $companyId;
 * @property string $country;
 * @property string $state;
 * @property string $city;
 * @property string $zipCode;
 * @property string $address;
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
            'country' => 'nullable',
            'state' => 'nullable',
            'city' => 'nullable',
            'zipCode' => 'nullable',
            'address' => 'nullable',
        ];
    }
}
