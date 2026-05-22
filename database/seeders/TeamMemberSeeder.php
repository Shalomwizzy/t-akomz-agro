<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name'       => 'Team Member 1',
                'role'       => 'Founder & CEO',
                'bio'        => 'Leading T-Akomz Agro Estates with passion for sustainable agriculture.',
                'image'      => 'our-team/IMG_6150.jpg',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'name'       => 'Team Member 2',
                'role'       => 'Farm Manager',
                'bio'        => 'Overseeing daily farm operations and ensuring the highest quality standards.',
                'image'      => 'our-team/IMG_6151.jpg',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'name'       => 'Team Member 3',
                'role'       => 'Operations Manager',
                'bio'        => 'Managing logistics, supply chain and delivery operations.',
                'image'      => 'our-team/IMG_6157.jpg',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'name'       => 'Team Member 4',
                'role'       => 'Sales & Marketing',
                'bio'        => 'Connecting customers with the finest farm-fresh products.',
                'image'      => 'our-team/IMG_6158.jpg',
                'sort_order' => 4,
                'is_active'  => true,
            ],
        ];

        foreach ($members as $member) {
            TeamMember::firstOrCreate(['image' => $member['image']], $member);
        }
    }
}
