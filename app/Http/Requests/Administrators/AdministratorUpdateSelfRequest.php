<?php

namespace App\Http\Requests\Administrators;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class AdministratorUpdateSelfRequest extends BaseRequest
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
                'filled',
                'regex:/^[a-zA-Z\d_]{6,20}$/'
            ],
            //required_with:字段   指定字段存在时，验证字段必须存在且不能为空
            'confirm_password' => 'required_with:password|same:password',
            'name' => 'filled|between:3,8',
            'avatar' => 'filled',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'password' => '密码',
            'confirm_password' => '确认密码',
            'name' => '名称',
            'avatar' => '头像',
        ];
    }
}
