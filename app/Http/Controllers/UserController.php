<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class UserController extends Controller
{
    public function list()
    {
        $data = Admin::with('role')->get();

        return view('admin.list', compact('data'));
    }
}
