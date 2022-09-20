<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Authorization;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Route::currentRouteName()) {
            $user = Auth::user();
            $route = explode('|',Route::currentRouteName());
            $module = Module::where('name',$route[0])->first();
            $permission = Authorization::where('role_id',$user->role_id)->where('module_id',$module->id)->first();
            $type = $route[1];
    
            if (!$permission->{$type}) {
                return redirect('/');
            }
        }
        return $next($request);
    }
}
