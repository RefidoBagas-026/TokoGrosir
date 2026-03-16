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
        $staff = Role::where('name', 'staff')->first();

        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('123'),
            'role_id' => $superadmin->id,
        ]);

        // Admin
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
            'role_id' => $admin->id,
        ]);

        // Staff
        User::create([
            'name' => 'Staff Sales',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('123'),
            'role_id' => $staff->id,
        ]);
    }
}
