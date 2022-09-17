<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Authorization;
use App\Models\Role;
use App\Models\Module;

class AuthorizationSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = Module::all();
        $admin = Role::where('name','admin')->first();

        foreach ($modules as $module) {
            $check = Authorization::where('role_id',$admin->id)->where('module_id',$module->id)->first();
            if (!$check) {
                $data = new Authorization;
                $data->role_id = $admin->id;
                $data->module_id = $module->id;
                $data->view = 1;
                $data->add = 1;
                $data->edit = 1;
                $data->delete = 1;
                $data->save();
            }
        }
    }
}
