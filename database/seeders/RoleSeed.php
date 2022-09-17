<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin','cashier'];

        foreach ($roles as $role) {
            if (!Role::where('name',$role)->first()) {
                $data = new Role;
                $data->name = $role;
                $data->save();
            }
        }
    }
}
