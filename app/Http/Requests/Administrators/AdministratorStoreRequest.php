<?php

namespace App\Http\Requests\Administrators;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class AdministratorStoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:administrators,username',
            'password' => [
                'required',
                'regex:/^[a-zA-Z\d_]{6,20}$/'
            ],
            'confirm_password' => 'required|same:password',
            'name' => 'required|between:3,8',
            'avatar' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'username' => '账号',
            'password' => '密码',
            'confirm_password' => '确认密码',
            'name' => '名称',
            'avatar' => '头像',
        ];
    }
}
