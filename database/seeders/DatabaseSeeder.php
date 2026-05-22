<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            SiteSettingSeeder::class,
            BlogPostSeeder::class,
            CouponSeeder::class,
            FinanceSeeder::class,
            TeamMemberSeeder::class,
        ]);
    }
}
