<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::attempt($request->only(['username','password']))) {
                $request->session()->regenerate();
                return redirect('/');
            }

            return redirect('/auth/login')->with('error','The provided credentials do not match our records');
        } catch(\Exception $e) {
            return redirect('/auth/login')->with('error', $e->getMessage());
        }
    }
}
