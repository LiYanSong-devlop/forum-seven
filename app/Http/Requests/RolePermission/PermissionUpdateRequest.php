<?php

namespace App\Http\Requests\RolePermission;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class PermissionUpdateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'guard_name' => 'filled|in:admin,api',
            'order' =>'filled|integer|max:999|min:1',
            'state' => 'filled|integer|in:0,1',
            'title' => 'filled|min:5|max:15',
            'parent_id' => 'filled|exists:permissions,id'
        ];
    }

    public function attributes()
    {
        return [
            'guard_name' => '分组名称',
            'order' => '排序',
            'state' => '状态',
            'title' => '中文标签',
            'parent_id' => '父级',
        ];
    }
}
