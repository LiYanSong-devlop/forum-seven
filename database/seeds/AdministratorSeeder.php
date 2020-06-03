<?php

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = time();
        DB::table('administrators')->insert([
            'username' => 'liysong',
            'password' => bcrypt('liysong'),
            'name' => 'Liysong',
            'is_main' => 1,
            'created_at' => date('Y-m-d H:i:s',$time),
            'updated_at' => date('Y-m-d H:i:s',$time),
        ]);
    }
}
