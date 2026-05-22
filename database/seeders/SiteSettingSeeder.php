<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'delivery_fee_standard'  => '1500',
            'delivery_fee_express'   => '3500',
            'delivery_fee_pickup'    => '0',
            'min_order_amount'       => '2000',
            'contact_phone'          => '+234 800 000 0000',
            'contact_phone_2'        => '+234 800 000 0001',
            'contact_email'          => 'hello@takomzagro.com',
            'contact_address'        => 'Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria',
            'whatsapp_number'        => '2348000000000',
            'social_instagram'       => 'https://instagram.com/takomzagro',
            'social_facebook'        => 'https://facebook.com/takomzagro',
            'social_twitter'         => 'https://twitter.com/takomzagro',
            'social_youtube'         => '',
            'banner_text'            => '🌿 Free delivery on orders above ₦15,000 within Lagos!',
            'banner_active'          => '1',
            'maintenance_mode'       => '0',
            'cod_allowed_states'     => json_encode(['Lagos', 'Ogun']),
            'bank_account_name'      => 'T-Akomz Agro Estates Ltd',
            'bank_account_number'    => '0000000000',
            'bank_name'              => 'First Bank of Nigeria',
            'google_maps_embed'      => '',
            'about_founding_year'    => '2018',
            'about_acres'            => '50',
            'about_chickens'         => '500',
            'about_eggs_daily'       => '1000',
            'about_customers'        => '200',
            // Payment gateway: 'paystack' | 'flutterwave' | 'both'
            'active_payment_gateway' => 'paystack',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
