<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!Admin::where('username','admin')->first()) {
            $admin = new Admin;
            $admin->username = 'admin';
            $admin->password = bcrypt('123');
            $admin->role_id = Role::where('name','admin')->first()->id;
            $admin->save();
        }
    }
}
