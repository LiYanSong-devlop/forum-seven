<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            return $this->failed('请重新登录', 401);
        }

        if ($exception instanceof TokenInvalidException) {
            return $this->failed('用户未登录', 401);
        }

        if ($exception instanceof ValidationException) {
            return $this->failed($exception->validator->errors()->first(), 400);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->failed('非法请求', 404);
        }

        if ($exception instanceof ApiException) {
            return $this->failed($exception->msg, $exception->code);
        }
        if ($exception instanceof ConnectException) {
            return $this->failed('创建失败，请稍候重试', 400);
        }
        if ($exception instanceof BindingResolutionException) {
            return $this->failed($exception->getMessage(),500);
        }
        if ($exception instanceof UnauthorizedException) {
            return $this->failed($exception->getMessage(),403);
        }
        if ($exception instanceof \Exception) {
            return $this->failed($exception->getMessage());
        }

        /*if ($exception->getMessage() == "Too Many Attempts.") {
        }*/
        return parent::render($request, $exception);
    }
}
