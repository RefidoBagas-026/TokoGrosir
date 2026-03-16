<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RolePermission;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $menuCode): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        // superadmin bebas
        if ($user->role && $user->role->name === 'superadmin') {
            return $next($request);
        }

        $hasPermission = RolePermission::where('role_id', $user->role_id)
            ->whereHas('menu', function ($query) use ($menuCode) {
                $query->where('menu_code', $menuCode);
            })
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Tidak memiliki akses');
        }

        return $next($request);
    }
}
