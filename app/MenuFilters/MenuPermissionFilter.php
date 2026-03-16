<?php

namespace App\MenuFilters;

use Illuminate\Support\Facades\Auth;
use App\Models\RolePermission;
use App\Models\Menu;

class MenuPermissionFilter
{
    public function transform($item)
    {
        if (!isset($item['menu_code'])) {
            return $item;
        }

        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ($user->role && $user->role->name === 'superadmin') {
            return $item;
        }

        $hasPermission = RolePermission::where('role_id', $user->role_id)
            ->whereHas('menu', function ($query) use ($item) {
                $query->where('menu_code', $item['menu_code']);
            })
            ->exists();

        return $hasPermission ? $item : false;
    }
}