<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermission\PermissionUpdateRequest;
use App\Http\Resources\RolePermission\PermissionResource;
use App\Http\Resources\RolePermission\PermissionResourceCollection;
use App\Models\RolePermission\Permission;
use App\Service\RolePermission\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends ApiController
{
    //权限控制器
    protected $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    /**
     * 权限列表
     * 树状结构显示
     * 支持无限极
     * 不可以分页显示
     * @return mixed
     */
    public function index()
    {
        $list = $this->service->getQuery();
        return $this->success(PermissionResource::collection($list));
    }

    /**
     * 权限创建，
     * 不可以在程序中创建
     * @param Request $request
     */
    public function store(Request $request)
    {

    }

    /**
     * 权限详情
     * 关联子集获取
     * @param Permission $permission
     * @return mixed
     */
    public function show(Permission $permission)
    {
        //关联本身查询相关子集
        $permission->children;
        return $this->success(PermissionResource::make($permission));
    }

    /**
     * 更新权限
     * 只可以更新分组名称 排序 以及状态
     * @param PermissionUpdateRequest $request
     * @param Permission $permission
     * @return mixed
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        $requested_data = $request->only('order' ,'guard_name', 'state','title','parent_id');
        try {
            $permission->fill($requested_data)->save();
            return $this->success('更新成功');
        } catch (\Exception $exception) {
            return $this->failed('更新失败');
        }
    }

    /**
     * 删除
     * 递归删除
     * 支持无限极
     * @param Permission $permission
     * @return mixed
     * @throws \Throwable
     */
    public function destroy(Permission $permission)
    {
        \DB::beginTransaction();
        try {
            $this->service->del($permission);
            \DB::commit();
            return $this->success('删除成功');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return $this->failed('删除失败');
        }

    }
}
