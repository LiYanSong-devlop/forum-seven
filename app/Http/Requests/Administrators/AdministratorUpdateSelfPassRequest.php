<?php

namespace App\Http\Requests\Administrators;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class AdministratorUpdateSelfPassRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => [
                'required',
                'regex:/^[a-zA-Z\d_]{6,20}$/'
            ],
            'confirm_password' => 'required|same:password',
        ];
    }

    public function attributes()
    {
        return [
            'password' => '密码',
            'confirm_password' => '确认密码',
        ];
    }
}
