<?php


namespace App\Service\Administrator;


use App\Models\Administrator;

class AdministratorService
{
    protected $model;

    public function __construct(Administrator $model)
    {
        $this->model = $model;
    }

    public function getQuery()
    {
        $query = $this->model->query();
        if (request()->has('name')) {
            $query->where('name', 'like', '%' . request()->get('name') . '%');
        }
        if (request()->has('state') && in_array($state = request()->get('state'),[Administrator::CLOSE,Administrator::OPEN])) {
            $query->where('state', request()->get('state'));
        }
        if (request()->has('page')) {
            $list = $query->paginate(request()->get('per_page') ?? 10);
        }else{
            $list = $query->get();
        }
        return $list;
    }

    public function addAdministrator($basics_data)
    {
        return $this->model->create($basics_data);
    }
}
