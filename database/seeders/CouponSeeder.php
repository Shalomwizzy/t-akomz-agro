<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code'            => 'WELCOME10',
                'type'            => 'PERCENTAGE',
                'value'           => 10,
                'min_order_value' => 5000,
                'max_uses'        => null,
                'valid_from'      => '2025-01-01 00:00:00',
                'valid_until'     => '2030-12-31 23:59:59',
                'is_active'       => true,
            ],
            [
                'code'            => 'FRESH500',
                'type'            => 'FIXED',
                'value'           => 500,
                'min_order_value' => 3000,
                'max_uses'        => 200,
                'valid_from'      => '2025-01-01 00:00:00',
                'valid_until'     => '2030-12-31 23:59:59',
                'is_active'       => true,
            ],
            [
                'code'            => 'FREESHIP',
                'type'            => 'FREE_DELIVERY',
                'value'           => 0,
                'min_order_value' => 10000,
                'max_uses'        => null,
                'valid_from'      => '2025-01-01 00:00:00',
                'valid_until'     => '2030-12-31 23:59:59',
                'is_active'       => true,
            ],
        ];

        foreach ($coupons as $data) {
            Coupon::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
