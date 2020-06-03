<?php

namespace App\Http\Requests\RolePermission;

use App\Http\Requests\BaseRequest;

class SetAdministratorRoleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_ids' => 'required|array',
            'role_ids.*' => 'required|integer|exists:roles,id'
        ];
    }

    public function attributes()
    {
        return [
            'roles_ids' => '角色',
            'role_ids.*' => '角色中ID',
        ];
    }
}
