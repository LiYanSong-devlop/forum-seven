<?php

namespace App\Http\Controllers\Admin\Administrator;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Administrators\AdministratorStoreRequest;
use App\Http\Requests\Administrators\AdministratorUpdateOtherRequest;
use App\Http\Requests\Administrators\AdministratorUpdateSelfPassRequest;
use App\Http\Requests\Administrators\AdministratorUpdateSelfRequest;
use App\Http\Resources\Administrator\AdministratorResource;
use App\Http\Resources\Administrator\AdministratorResourceCollection;
use App\Http\Resources\Administrator\AdministratorRoleResource;
use App\Models\Administrator;
use App\Service\Administrator\AdministratorService;
use Illuminate\Http\Request;

class AdministratorController extends ApiController
{
    //管理员控制器
    protected $service;

    public function __construct(AdministratorService $service)
    {
        $this->service = $service;
    }

    /**
     * 管理员列表
     * 查询条件：姓名、状态
     * 支持分页
     * @return mixed
     */
    public function index()
    {
        $list = $this->service->getQuery();
        if (\request()->has('page')) {
            return $this->success(AdministratorResourceCollection::make($list));
        }else{
            return $this->success(AdministratorResource::collection($list));
        }
    }

    /**
     * 创建管理员
     * 判断当前用户是否是超级管理员，不是超级管理员不可以创建
     * 密码使用 bcrypt() 加密
     * @param AdministratorStoreRequest $request
     * @return mixed
     */
    public function store(AdministratorStoreRequest $request)
    {
        //判断是否是超级管理员，即为主体账号
        $user = auth('admin')->user();
        if (!$user->compareIsMain()) {
            return $this->failed('当前用户（管理员），不是超级管理员，不允许添加管理员');
        }
        $basics_data = $request->only('username','password','name','avatar');
        $this->service->addAdministrator($basics_data);
        return $this->success('创建成功');
    }

    /**
     * 管理员详情
     * @param Administrator $administrator
     * @return mixed
     */
    public function show(Administrator $administrator)
    {
        return $this->success(AdministratorResource::make($administrator));
    }

    /**
     * 管理员删除
     * @param Administrator $administrator
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Administrator $administrator)
    {
        $super = auth('admin')->user();
        if (!$super->compareIsMain()) {
            return $this->failed('当前管理员不是超级管理员，无法修改他人相关信息');
        }
        if ($administrator->delete()) {
            return $this->success('删除成功');
        }else{
            return $this->failed('删除失败');
        }
    }

    /**
     * 修改自身密码
     * TODO 不需要重新登录
     * @param AdministratorUpdateSelfPassRequest $request
     * @return mixed
     */
    public function updateSelfPass(AdministratorUpdateSelfPassRequest $request)
    {
        $administrator = auth('admin')->user();
        $basics_data = $request->only('password');
        $result = $administrator->fill($basics_data)->save();
        if ($result) {
            return $this->success('修改密码成功');
        }else{
            return $this->failed('修改密码失败');
        }
    }

    /**
     * 修改其他管理员
     * @param AdministratorUpdateOtherRequest $request
     * @param Administrator $administrator
     * @return mixed
     */
    public function updateOther(AdministratorUpdateOtherRequest $request,Administrator $administrator)
    {
        $super = auth('admin')->user();
        if (!$super->compareIsMain()) {
            return $this->failed('当前管理员不是超级管理员，无法修改他人相关信息');
        }
        $basics_data = $request->only('username','password','name','avatar');
        if ($administrator->fill($basics_data)->save()) {
            return $this->success('更新成功');
        }else{
            return $this->failed('更新失败');
        }
    }

    /**
     * 管理员修改 只可以修改本身的信息
     * @param AdministratorUpdateSelfRequest $request
     * @return mixed
     */
    public function updateSelf(AdministratorUpdateSelfRequest $request)
    {
        $administrator = auth('admin')->user();
        $basics_data = $request->only('password', 'name', 'avatar');
        if ($administrator->fill($basics_data)->save()) {
            return $this->success('更新成功');
        }else{
            return $this->failed('更新失败');
        }
    }

    /**
     * 获取某管理员的角色权限
     * @param Administrator $administrator
     * @return mixed
     */
    public function getRoleByAdminId(Administrator $administrator)
    {
        $administrator->getPermissionsViaRoles();
        return $this->success(AdministratorRoleResource::make($administrator));
    }

    /**
     * 获取当前管理员的角色权限
     * @return mixed
     */
    public function getRoleBySelf()
    {
        $administrator = auth('admin')->user();
        $administrator->getPermissionsViaRoles();
        return $this->success(AdministratorRoleResource::make($administrator));
    }

}
