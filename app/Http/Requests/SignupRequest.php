<?php

namespace App\Http\Requests;

/**
 * Class SignupRequest
 * @package App\Http\Requests
 *
 * @property string $firstName;
 * @property string $lastName;
 * @property string $email;
 * @property string $username;
 * @property string $password;
 */
class SignupRequest extends BaseRequest
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
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'username' => 'required|min:5',
            'password' => 'required|min:8',
        ];
    }
}
