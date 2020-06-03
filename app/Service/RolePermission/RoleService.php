<?php


namespace App\Service\RolePermission;



use App\Models\RolePermission\Role;

class RoleService
{
    protected $model;
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * 列表
     * 通过名称查询
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getQuery()
    {
        $query = $this->model->query();
        //通过名称模糊查询
        if (request()->has('name')) {
            $query->where('name', 'like', '%' . request()->get('name') . '%');
        }
        if (request()->has('page')) {
            $list = $query->paginate(request()->get('per_page') ?? 10);
        }else{
            $list = $query->get();
        }
        return $list;
    }


    public function add($data,array $other_data = [])
    {
        \DB::beginTransaction();
        try {
            $role = $this->model->create($data);
            if (!empty($other_data)) {
                $role->permissions()->attach($other_data);
            }
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

    /**
     * @param Role $role
     * @param array $basics_data ['name' => 1, 'guard_name'=>2]
     * @param array $other_data [1,2,3,4]
     * @return bool
     * @throws \Throwable
     */
    public function updateDataAndPermission(Role $role,array $basics_data,array $other_data = [])
    {
        \DB::beginTransaction();
        try {
            if (!empty($other_data)) {
                $role->permissions()->sync($other_data);
            }
            $role->fill($basics_data)->save();
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }
}
