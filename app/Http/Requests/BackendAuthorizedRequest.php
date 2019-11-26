<?php

namespace App\Http\Requests;

/**
 * Class BackendAuthorizedRequest
 * @package App\Http\Requests
 *
 * @property \App\User $user;
 */
class BackendAuthorizedRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
