<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = ['transaction','product_category','product','voucher','role','authorization','user'];

        foreach ($modules as $module) {
            if (!Module::where('name',$module)->first()) {
                $data = new Module;
                $data->name = $module;
                $data->save();
            }
        }
    }
}
