<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\Models\Admin;
        $admin->username = 'myadmin';
        $admin->password = \Illuminate\Support\Facades\Hash::make('123456');
        $admin->avatar = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $admin->name = 'admin';
        $admin->save();

        $admin2 = new \App\Models\Admin;
        $admin2->name = 'test';
        $admin2->username = 'test@ity.com';
        $admin2->password = \Illuminate\Support\Facades\Hash::make('123456');
        $admin2->avatar = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $admin2->save();
    }
}
