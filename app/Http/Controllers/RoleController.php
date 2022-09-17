<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function list()
    {
        $data = Role::all();

        return view('role.list', compact('data'));
    }
}
