<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        $user = Auth::user();

        /**
         * BELUM LOGIN
         */
        if (!$user) {

            return redirect()->route('login');
        }

        /**
         * AKUN NONAKTIF
         */
        if ($user->status !== 'aktif') {

            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Akun anda nonaktif.',
                ]);
        }

        /**
         * ROLE USER KOSONG
         */
        if (empty($user->roles)) {

            abort(403, 'Role akun belum diatur.');
        }

        /**
         * ROLE AKTIF
         */
        $activeRole = session('active_role');

        /**
         * BELUM PILIH ROLE
         */
        if (!$activeRole) {

            // jika cuma 1 role langsung pakai
            if (count($user->roles) === 1) {

                $activeRole = $user->roles[0];

                session([
                    'active_role' => $activeRole
                ]);

            } else {

                return redirect()
                    ->route('role.select');
            }
        }

        /**
         * CEK APAKAH ROLE AKTIF
         * MILIK USER
         */
        if (!in_array($activeRole, $user->roles)) {

            session()->forget('active_role');

            return redirect()
                ->route('role.select');
        }

        /**
         * CEK AKSES ROLE
         *
         * contoh:
         * middleware('role:admin')
         * middleware('role:guru,wali_kelas')
         */
        if (!in_array($activeRole, $roles)) {

            abort(403, 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}