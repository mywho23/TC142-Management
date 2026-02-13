<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function loginPage()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('pages.login');
    }


    // Proses login
    public function loginProcess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Ambil input user
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        // Coba login
        if (Auth::attempt($credentials, $request->remember)) {
            return redirect()->intended('/'); // redirect ke dashboard
        }

        // Jika gagal login
        return back()->with('error', 'Username atau password salah.');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login');
    }
}
