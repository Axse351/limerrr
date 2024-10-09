<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.pages.register');
    }

    public function registerStore(Request $request)
    {
        $messages = [
            'name.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.unique' => 'Email sudah dimiliki, silahkan login!',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password maksimal 16 karakter',
        ];

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:16',
            'role' => 'in:admin,staff,scan1,scan2', // Validasi role
        ], $messages);  

        // Default role is 'staff' if not provided
        $role = $request->role ?? 'staff';

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        return redirect('/login')->with('success', 'Pendaftaran berhasil, silahkan login!');
    }

    public function login()
    {
        return view('auth.pages.login');
    }

    public function loginStore(Request $request)
    {
        $messages = [
            'email.required' => 'Email tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ];

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $messages);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();
            // return redirect('/rektorat/dashboard');
        
            if (Auth::user()->role == 'admin') {
                return redirect('admin/dashboard');
            } elseif (Auth::user()->role == 'staff') {
                return redirect('staff/dashboard');
            } elseif (Auth::user()->role == 'scan1') {
                return redirect('scan1/dashboard'); // Sesuaikan URL ini dengan rute yang Anda inginkan
            } elseif (Auth::user()->role == 'scan2') {
                return redirect('scan2/dashboard'); // Sesuaikan URL ini dengan rute yang Anda inginkan
            }
        }else {
            return back()->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logout Berhasil');
    }
}
