<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Role;

class UserController extends Controller
{
    public function list()
    {
        $data = Admin::with('role')->get();

        return view('admin.list', compact('data'));
    }

    public function form(Request $request, $id = null)
    {
        $roles = Role::all();
        $view = view('admin.form')->with('roles',$roles);
        if($id) {
            $data = Admin::where('id',$id)->first();
            if(!$data) return redirect('/authorization/user');
            $view = $view->with('data',$data);
        }

        return $view;
    }

    public function save(Request $request, $id = null)
    {
        \DB::beginTransaction();
        try {
            if (!$id) {
                if (!$request->filled('username')) throw new \Exception('Username field must be filled');
                if (!$request->filled('password')) throw new \Exception('Password field must be filled');
                if (!$request->filled('role') || $request->role == '0') throw new \Exception('Role field must be filled');
                if (Admin::where('username',$request->username)->first()) throw new \Exception('Username already registered');
            }
            if (!Role::where('id',$request->role)->first()) throw new \Exception('Role not found');

            if (!$id) {
                $user = new Admin;
                $user->username = $request->username;
                $user->password = bcrypt($request->password);
            } else {
                $user = Admin::where('id',$id)->first();
                if (!$user) throw new \Exception('User not found');
            }
            $user->role_id = $request->role;
            $user->save();

            \DB::commit();
            return redirect('/authorization/user')->with('success','User saved successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        \DB::beginTransaction();
        try {
            if (!$request->filled('id')) return redirect()->back();

            $user = Admin::where('id',$request->id)->first();
            if (!$user) throw new \Exception('User not found');
            
            $user->delete();

            \DB::commit();
            return redirect('/authorization/user')->with('success','User deleted successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
