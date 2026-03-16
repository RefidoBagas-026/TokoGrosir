<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class MenuPermissionController extends Controller
{
    public function index()
    {
        $roles = Role::where('name', '!=', 'superadmin')->get();
        $menus = Menu::all();

        // Build a lookup: role_id => [menu_id => true]
        $permissions = [];
        $rolePermissions = RolePermission::all();
        foreach ($rolePermissions as $rp) {
            $permissions[$rp->role_id][$rp->menu_id] = true;
        }

        return view('menu-permission.index', compact('roles', 'menus', 'permissions'));
    }

    public function update(Request $request)
    {
        try {
            $roles = Role::where('name', '!=', 'superadmin')->get();
            $menus = Menu::all();

            // submitted permissions: ['role_id' => ['menu_id', 'menu_id', ...]]
            $submittedPermissions = $request->input('permissions', []);

            foreach ($roles as $role) {
                $allowedMenuIds = $submittedPermissions[$role->id] ?? [];

                foreach ($menus as $menu) {
                    $exists = RolePermission::where('role_id', $role->id)
                        ->where('menu_id', $menu->id)
                        ->exists();

                    if (in_array((string) $menu->id, $allowedMenuIds)) {
                        // Should have permission - create if not exists
                        if (!$exists) {
                            RolePermission::create([
                                'role_id' => $role->id,
                                'menu_id' => $menu->id,
                            ]);
                        }
                    } else {
                        // Should NOT have permission - delete if exists
                        if ($exists) {
                            RolePermission::where('role_id', $role->id)
                                ->where('menu_id', $menu->id)
                                ->delete();
                        }
                    }
                }
            }

            return redirect()->route('menu-permission.index')->with('success', 'Berhasil update hak akses menu');
        } catch (\Throwable $th) {
            return redirect()->route('menu-permission.index')->with('error', $th->getMessage());
        }
    }
}
