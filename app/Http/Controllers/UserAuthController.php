<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function showUserLogin()
    {
        return view('login-user'); // pastikan file blade-nya ada di resources/views/login-user.blade.php
    }

    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // cek apakah input email atau nomor telepon
        $fieldType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$fieldType => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/user/dashboard');
        }

        return back()->with('error', 'Email/No. Telepon atau password salah.');
    }

    public function userLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegister()
    {
        return view('register-user');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[\W_]/',
                'confirmed',
            ],
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        // ✅ Login langsung setelah register
        Auth::login($user);

        // 📩 Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        // 🚀 Arahkan ke halaman verifikasi email
        return redirect()->route('verification.notice')
                        ->with('message', 'Akun berhasil dibuat! Silakan cek email Anda untuk verifikasi.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui ✅');
    }

}
