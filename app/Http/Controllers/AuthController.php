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
            'email.unique' => 'email sudah dimiliki, silahkan login!',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password maksimal 16 karakter',
        ];

        $request->validate([
            'email' => 'required|integer|unique:users',
            'password' => 'required|min:8|max:16',
        ], $messages);


        if ($request->email != null) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'staff',
            ]);

            return redirect('/login');
        } else {
            $user = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);

            return redirect('/login');
        }
    }

    public function login()
    {
        return view('auth.pages.login');
    }

    public function loginStore(Request $request)
    {
        $messages = [
            'email.required' => 'email tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ];

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], $messages);

        $credentials = $request->only('email', 'password');

        // $credentials = [
        //     'name' => $request->name,
        //     'password' => $request->password,
        // ];

        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();
            // return redirect('/rektorat/dashboard');

            if (Auth::user()->role == 'admin') {
                return redirect('admin/dashboard');
            } elseif (Auth::user()->role == 'staff') {
                return redirect('staff/dashboard');
            }
        } else {
            return back()->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logout Berhasil');
    }
}
