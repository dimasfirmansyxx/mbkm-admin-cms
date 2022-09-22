<?php

use App\Models\Module;
use App\Models\Authorization;

if (!function_exists('permission')) {
    function permission($user, $module, $type)
    {
        $module = Module::where('name',$module)->first();
        $authorization = Authorization::where('role_id',$user->role_id)->where('module_id',$module->id)->first();
        if (!$authorization) return false;
        
        return $authorization->{$type};
    }
}