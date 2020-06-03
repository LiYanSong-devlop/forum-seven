<?php


namespace App\Http\Controllers\Test;


use App\Http\Controllers\ApiController;
use App\Service\Base\SensitiveService;

class TestController extends ApiController
{
    public function sensitive()
    {
        $data = request()->get('text');
        $result = SensitiveService::sensitiveWordFilter($data);
        return $this->success($result);
    }
}
