<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showAdminLogin()
    {
        return view('auth.login-admin');
    }

    public function adminLogin(Request $request)
    {
        $username = $request->input('username');
        $password = md5($request->input('password'));

        $admin = DB::table('admin')
            ->where('username', $username)
            ->where('password', $password)
            ->first();

        if ($admin) {
            Session::put('admin', $admin->username);
            return redirect('/admin/dashboard');
        } else {
            return back()->with('error', 'Username atau password salah!');
        }
    }

    public function adminLogout()
    {
        Session::forget('admin');
        return redirect('/admin/login');
    }
}
