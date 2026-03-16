<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RoleMenuPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $kasir = Role::firstOrCreate(['name' => 'kasir']);

        // Create menus
        $menus = [
            ['name' => 'Dashboard', 'menu_code' => 'M1', 'route' => 'dashboard'],
            ['name' => 'Barang', 'menu_code' => 'M2', 'route' => 'product'],
            ['name' => 'Pembelian', 'menu_code' => 'M3', 'route' => 'purchasing'],
            ['name' => 'Stock', 'menu_code' => 'M4', 'route' => 'stock'],
            ['name' => 'Penjualan', 'menu_code' => 'M5', 'route' => 'sales'],
            ['name' => 'Hutang', 'menu_code' => 'M6', 'route' => 'debt'],
            ['name' => 'Kelola User', 'menu_code' => 'M7', 'route' => 'user'],
            ['name' => 'Hak Akses Menu', 'menu_code' => 'M8', 'route' => 'menu-permission'],
        ];

        foreach ($menus as $menuData) {
            Menu::firstOrCreate(['menu_code' => $menuData['menu_code']], $menuData);
        }

        // Admin: access to all menus
        $allMenus = Menu::all();
        foreach ($allMenus as $menu) {
            RolePermission::firstOrCreate([
                'role_id' => $admin->id,
                'menu_id' => $menu->id,
            ]);
        }

        // Kasir: only Dashboard + Penjualan + Hutang
        $kasirMenuCodes = ['M1', 'M5', 'M6'];
        $kasirMenus = Menu::whereIn('menu_code', $kasirMenuCodes)->get();
        foreach ($kasirMenus as $menu) {
            RolePermission::firstOrCreate([
                'role_id' => $kasir->id,
                'menu_id' => $menu->id,
            ]);
        }
    }
}
