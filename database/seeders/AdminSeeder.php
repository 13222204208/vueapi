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
        $admin->save();
    }
}
