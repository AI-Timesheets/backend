<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ChangePasswordRecoveryRequest
 * @package App\Http\Requests
 *
 * @property string $recoveryKey;
 * @propertt string $password;
 */
class ChangePasswordRecoveryRequest extends FormRequest
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
            'recoveryKey' => 'required',
            'password' => 'required|min:8',
        ];
    }
}
