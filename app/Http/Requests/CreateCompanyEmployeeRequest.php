<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateCompanyEmployeeRequest
 * @package App\Http\Requests
 *
 * @property string $firstName;
 * @property string $lastName;
 * @property double $hourlyWage;
 * @property inte $locationId;
 */
class CreateCompanyEmployeeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'firstName' => 'required',
            'lastName' => 'required',
            'hourlyWage' => 'required|numeric',
            'locationId' => 'required|integer',
            'isAdmin' => 'required|boolean',
            'loginCode' => 'required|integer',
        ];
    }
}
