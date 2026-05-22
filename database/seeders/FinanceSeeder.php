<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        // Create MANAGER role
        $manager    = Role::firstOrCreate(['name' => 'MANAGER', 'guard_name' => 'web']);
        $admin      = Role::where('name', 'ADMIN')->first();
        $superAdmin = Role::where('name', 'SUPER_ADMIN')->first();

        // Create permissions
        $permissionNames = [
            'wallet.view',
            'wallet.fund',
            'expense.create',
            'expense.approve',
            'expense.view_all',
            'report.export',
        ];

        foreach ($permissionNames as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Assign to roles
        $manager->syncPermissions(['wallet.view', 'expense.create']);

        if ($admin) {
            $admin->givePermissionTo($permissionNames);
        }

        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissionNames);
        }

        // Create default wallet
        \App\Models\Wallet::firstOrCreate(['name' => 'T-Akomz Farm Wallet'], [
            'balance'      => 0,
            'total_funded' => 0,
            'total_spent'  => 0,
        ]);

        // Create demo manager user
        $demoManager = \App\Models\User::firstOrCreate(
            ['email' => 'manager@takomzagro.com'],
            [
                'name'     => 'Farm Manager',
                'password' => bcrypt('Manager@2024!'),
                'phone'    => '08012345678',
            ]
        );

        if (!$demoManager->hasRole('MANAGER')) {
            $demoManager->assignRole('MANAGER');
        }

        $this->command->info('FinanceSeeder completed.');
        $this->command->info('  MANAGER role: created');
        $this->command->info('  Permissions: ' . implode(', ', $permissionNames));
        $this->command->info('  Demo manager: manager@takomzagro.com / Manager@2024!');
    }
}
