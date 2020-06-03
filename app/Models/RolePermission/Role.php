<?php

namespace App\Models\RolePermission;

use Illuminate\Database\Eloquent\SoftDeletes;
use \Spatie\Permission\Models\Role as RoleModel;

class Role extends RoleModel
{
    //
    use SoftDeletes;
}
