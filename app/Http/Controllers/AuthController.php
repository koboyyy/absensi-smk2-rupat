<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * FORM LOGIN
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * PROSES LOGIN
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([

            'username' => [
                'required',
                'string'
            ],

            'password' => [
                'required',
                'string'
            ],

        ]);

        $remember = (bool) $request->boolean('remember');

        /**
         * ATTEMPT LOGIN
         */
        if (Auth::attempt($credentials, $remember)) {

            $request->session()->regenerate();

            $user = Auth::user();

            // AKUN NONAKTIF
            if ($user?->status !== 'aktif') {

                Auth::logout();

                return back()->withErrors([
                    'username' => 'Akun anda nonaktif.',
                ]);
            }

            // ROLE KOSONG
            if (empty($user->roles)) {

                Auth::logout();

                return back()->withErrors([
                    'username' => 'Role akun belum diatur.',
                ]);
            }

            /**
             * JIKA ROLE LEBIH DARI 1
             */
            if (count($user->roles) > 1) {

                return redirect()->route('role.select');
            }

            /**
             * JIKA HANYA 1 ROLE
             */
            session([
                'active_role' => $user->roles[0]
            ]);

            return redirect()->route('dashboard');
        }
        /**
         * LOGIN GAGAL
         */
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function selectRole()
    {
        $user = Auth::user();

        return view('auth.select-role', compact('user'));
    }

    public function setRole(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'role' => ['required']
        ]);

        // pastikan role milik user
        if (!in_array($request->role, $user->roles)) {

            abort(403);
        }

        session([
            'active_role' => $request->role
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}