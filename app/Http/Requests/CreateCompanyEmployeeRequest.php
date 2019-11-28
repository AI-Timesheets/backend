<?php

namespace App\Http\Requests;

/**
 * Class CreateCompanyEmployeeRequest
 * @package App\Http\Requests
 *
 * @property string $firstName;
 * @property string $lastName;
 * @property double $hourlyWage;
 * @property int $locationId;
 * @property bool $isAdmin;
 * @property int $loginCode;
 */
class CreateCompanyEmployeeRequest extends BackendAuthorizedRequest
{
    public function rules()
    {
        return [
            'firstName' => 'required',
            'lastName' => 'required',
            'hourlyWage' => 'required|numeric',
            'locationId' => 'required|integer',
            'isAdmin' => 'required|boolean',
            'loginCode' => 'required|digits:4',
        ];
    }
}
