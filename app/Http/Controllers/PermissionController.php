<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Module;
use App\Models\Authorization;

class PermissionController extends Controller
{
    public function list(Request $request)
    {
        $roles = Role::all();

        $permissions = [];
        if ($request->filled('role') && $request->role != 0) {
            $modules = Module::orderBy('name','asc')->get();

            foreach ($modules as $module) {
                $tmp = (object) [
                    'id' => $module->id,
                    'name' => ucwords(str_replace('_',' ',$module->name)),
                    'view' => 0,
                    'add' => 0,
                    'edit' => 0,
                    'delete' => 0
                ];

                $authorization = Authorization::where('role_id',$request->role)->where('module_id',$module->id)->first();
                if ($authorization) {
                    $tmp->view = $authorization->view;
                    $tmp->add = $authorization->add;
                    $tmp->edit = $authorization->edit;
                    $tmp->delete = $authorization->delete;
                }

                $permissions[] = $tmp;
            }
        }

        return view('permission.list')->with(compact('roles'))->with(compact('permissions'));
    }

    public function save(Request $request)
    {
        \DB::beginTransaction();
        try {
            if (!$request->filled('role')) return redirect()->back();
            $data = json_decode($request->data);
            
            $role = Role::where('id',$request->role)->first();
            if (!$role) return redirect()->back();

            foreach ($data as $row) {
                $permission = Authorization::where('module_id',$row->id)->where('role_id',$request->role)->first();
                if (!$permission) {
                    $permission = new Authorization;
                    $permission->role_id = $request->role;
                    $permission->module_id = $row->id;
                }

                $permission->view = $row->view;
                $permission->add = $row->add;
                $permission->edit = $row->edit;
                $permission->delete = $row->delete;
                $permission->save();
            }

            \DB::commit();

            return redirect()->back()->with('success','Permission saved successfully');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
