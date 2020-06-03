<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public $msg;
    public $code;

    function __construct($msg = 'Api数据异常', $code = 500)
    {
        parent::__construct($msg);

        $this->msg = $msg;
        $this->code = $code;
    }


    public function msg()
    {
        return $this->msg;
    }

    public function code()
    {
        return $this->code;
    }
}
