<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Superadmin bisa akses semua
        if ($user->role->name === 'superadmin') {
            return $next($request);
        }

        $hasPermission = RolePermission::where('role_id', $user->role_id)
            ->where('menu_id', $menuId)
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Tidak memiliki akses ke menu ini');
        }

        return $next($request);
    }
}
