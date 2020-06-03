<?php

namespace App\Models;

use App\Models\RolePermission\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Administrator extends Authenticatable implements JWTSubject
{
    use HasRoles;
    //定义常量 is_main相关
    const MAIN = 1; //主体账号
    const NOT = 0; //非主体账号
    //定义常量  state相关
    const OPEN = 1; //开启状态
    const CLOSE = 0; //禁用状态
    protected $fillable = [
        'username', 'password', 'name', 'avatar', 'state', 'is_main'
    ];
    /**
     * 隐藏某些特定字段
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * 定义相关字段为boolean
     * @var array
     */
    protected $casts = [
        'state' => 'boolean',
        'is_main' => 'boolean',
    ];


    /**
     * 监听创建事件
     * 如果数据库中相关字段设置了默认值得情况下可以不如此设置
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function (Administrator $administrator) {
            $administrator->state = self::OPEN;
            $administrator->is_main = self::NOT;
        });
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * 比较当前账号是不是主体账号
     * 是返回true
     * 否返回false
     * @return bool
     */
    public function compareIsMain()
    {
        return $this->is_main == self::MAIN;
    }

    /**
     * 比较当前账号是不是启用状态
     * 是返回true
     * 否返回false
     * @return bool
     */
    public function compareState()
    {
        return $this->state == self::OPEN;
    }

    /**
     * JWT 相关
     * 设置分组
     * 用来做前后端分表操作
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWT 相关
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'guard' => 'admin',
        ];
    }
}
