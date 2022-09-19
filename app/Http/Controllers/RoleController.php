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

    public function form(Request $request)
    {
        $view = view('role.form');

        if($request->filled('id')) {
            $data = Role::where('id',$request->id)->first();
            if(!$data) return redirect('/authorization/role');
            $view = $view->with('data', $data);
        }

        return $view;
    }

    public function save(Request $request)
    {
        \DB::beginTransaction();
        try {
            if(!$request->filled('name')) throw new \Exception('Role field must be filled');

            if($request->filled('id')) $role = Role::where('id',$request->id)->first();
            else $role = new Role;
    
            if(!$role) return redirect('/authorization/role');

            $role->name = $request->name;
            $role->save();

            \DB::commit();

            return redirect('/authorization/role')->with('success','Role saved successfully');
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

            $role = Role::where('id',$request->id)->first();
            if (!$role) throw new \Exception('ID Role not found');

            $role->delete();

            \DB::commit();

            return redirect('/authorization/role')->with('success','Role deleted successfully');
        } catch(\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
