<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleMenuPermissionSeeder::class);

        $superadmin = Role::where('name', 'superadmin')->first();
        $admin = Role::where('name', 'admin')->first();
        $kasir = Role::where('name', 'kasir')->first();

        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'password' => bcrypt('123'),
            'role_id' => $superadmin->id,
        ]);

        // Admin
        User::create([
            'name' => 'Admin Gudang',
            'username' => 'admin',
            'password' => bcrypt('123'),
            'role_id' => $admin->id,
        ]);

        // Kasir
        User::create([
            'name' => 'Kasir',
            'username' => 'kasir',
            'password' => bcrypt('123'),
            'role_id' => $kasir->id,
        ]);
    }
}
