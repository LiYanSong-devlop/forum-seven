<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('permissions')->delete();
        DB::table('permissions')->insert([
            [
                'name' => 'bbs',
                'guard_name' => 'admin',
                'method' => '',
                'path' => '/',
                'order' => 1,
                'state' => 1,
                'parent_id' => 0,
                'level' => 0,
                'title' => '系统管理',
            ],
            [
                'name' => 'user-manage',
                'guard_name' => 'admin',
                'method' => '',
                'path' => 'user-manage',
                'order' => 2,
                'state' => 1,
                'parent_id' => 0,
                'level' => 0,
                'title' => '管理员管理',
            ],
            [
                'name' => 'role-manage',
                'guard_name' => 'admin',
                'method' => '',
                'path' => 'role-manage',
                'order' => 3,
                'state' => 1,
                'parent_id' => 0,
                'level' => 0,
                'title' => '角色权限管理',
            ],
        ]);
    }
}
