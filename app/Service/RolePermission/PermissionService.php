<?php


namespace App\Service\RolePermission;

use App\Models\RolePermission\Permission;

class PermissionService
{
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * 树状结构列表
     * 不支持分页
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getQuery()
    {
        $query = $this->model->query()->with(['children'])->where('level',0);
        //中文标签模糊查询
        if (request()->has('title')) {
            $query->where('title', 'like', request()->get('title'));
        }
        //排序
        $query->orderByDesc('order');
        return $query->get();
    }

    /**
     * 递归删除
     * 存在下级关系
     * @param Permission $permission
     * @return bool|null
     * @throws \Exception
     */
    public function del(Permission $permission)
    {
        //判断是否存在下级
        if ($permission->children()->exists()) {
            $permission_children = $permission->children;
            foreach ($permission_children as $child) {
                $this->del($child);
            }
        }
        return $permission->delete();
    }
}
