<?php

namespace App\Http\Requests;

/**
 * Class LoginRequest
 * @package App\Http\Requests
 *
 * @property string $usernameOrEmail
 * @property string $password
 */
class MobileLoginRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'companyCode' => 'required',
        ];
    }
}
