<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'SUPER_ADMIN', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'ADMIN',       'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'CUSTOMER', 'guard_name' => 'web']);

        // Create super admin user — set ADMIN_PASSWORD in .env before seeding in production
        $password = env('ADMIN_PASSWORD');
        if (!$password) {
            $password = Str::random(24);
            $this->command->warn("No ADMIN_PASSWORD set in .env — generated: {$password}");
            $this->command->warn('Store this password securely; it will NOT be shown again.');
        }

        $user = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@takomzagro.com')],
            [
                'name'     => 'T-Akomz Admin',
                'phone'    => '+2348000000000',
                'password' => Hash::make($password),
            ]
        );

        $user->syncRoles([$superAdmin]);
    }
}
