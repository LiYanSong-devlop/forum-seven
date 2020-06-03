<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Permission;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class RoutePermissionMiddleware
{

    //角色权限中间件
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //获取当前请求方式
        $method = $request->method();
        $url_str = Str::startsWith($request->route()->uri,'/')?$request->route()->uri:'/'.$request->route()->uri;//请求路由
        $url = preg_replace_array('/{[a-z_0-9]+}/', ['*','*','*'], $url_str);
        $permission_name = Permission::where(['method'=>$method,'path'=>$url])->where('state',1)->select('name')->get();
        //判断当前用户是否存在该权限
        foreach ($permission_name as $item) {
            if (auth('admin')->user()->can($item->name)) {
                return $next($request);
            }else{
                throw UnauthorizedException::forPermissions(['permissions']);
            }

        }
        throw UnauthorizedException::forPermissions(['permissions']);
    }
}
