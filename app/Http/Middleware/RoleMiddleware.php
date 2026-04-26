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
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        // dd([
        //     'role_user_di_database' => $user->role,
        //     'role_yang_diminta_di_route' => $roles
        // ]);

        if (!$user) {
            return redirect()->route('login');
        }

        if ($roles !== [] && !in_array($user->role, $roles, true)) {
            abort(403);
        }

        if ($user->status !== 'aktif') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'username' => 'Akun anda nonaktif.',
            ]);
        }

        return $next($request);
    }
}
