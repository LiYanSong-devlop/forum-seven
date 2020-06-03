<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrators\Login\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends ApiController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username','password');
        if (!$token = auth('admin')->attempt($credentials)) {
            return $this->failed('用户名或密码不正确', 400);
        }
        //判断该管理员是否禁用
        $user = auth('admin')->user();
        if (!$user->compareState()) {
            return $this->failed('该管理员已经禁用',400);
        }
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }
}
