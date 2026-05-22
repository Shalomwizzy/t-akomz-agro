<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        ProductImage::truncate();

        // [product_id => [[file, is_primary, sort_order], ...]]
        $map = [
            2  => [ // Dressed Broiler Chicken
                ['dressed-broiler-1.jpg', true,  0],
                ['dressed-broiler-2.jpg', false, 1],
            ],
            24 => [ // Live Broiler Chicken
                ['live-broiler-1.jpg', true,  0],
                ['live-broiler-2.jpg', false, 1],
            ],
            3  => [ // Point-of-Lay Pullets
                ['pullets-1.jpg',    true,  0],
                ['live-broiler-2.jpg', false, 1],
            ],
            4  => [ // Day-Old Chicks (Broiler)
                ['day-old-chicks-1.jpg', true,  0],
                ['day-old-chicks-2.jpg', false, 1],
            ],
            5  => [ // Dressed Turkey
                ['dressed-turkey-1.jpg', true,  0],
                ['dressed-turkey-2.jpg', false, 1],
            ],
            6  => [ // Table Eggs - Small Crate (30 eggs)
                ['eggs-30-1.jpg', true,  0],
                ['eggs-30-2.jpg', false, 1],
                ['eggs-30-3.jpg', false, 2],
                ['eggs-30-4.jpg', false, 3],
            ],
            7  => [ // Table Eggs - Large Crate (60 eggs)
                ['eggs-60-1.jpg', true,  0],
                ['eggs-60-2.jpg', false, 1],
            ],
            8  => [ // Fertilized Hatching Eggs
                ['hatching-eggs-1.jpg', true,  0],
                ['hatching-eggs-2.jpg', false, 1],
            ],
            9  => [ // Quail Eggs
                ['quail-eggs-1.jpg', true, 0],
            ],
            10 => [ // Live Goat (Medium)
                ['live-goat-1.jpg', true,  0],
                ['live-goat-2.jpg', false, 1],
            ],
            11 => [ // Live Ram
                ['live-ram-1.jpg', true, 0],
            ],
            12 => [ // Dressed Pork (per kg)
                ['dressed-pork-1.jpg', true,  0],
                ['dressed-pork-2.jpg', false, 1],
                ['dressed-pork-3.jpg', false, 2],
            ],
            13 => [ // Fresh Maize (50kg bag)
                ['fresh-maize-1.jpg', true,  0],
                ['fresh-maize-2.jpg', false, 1],
            ],
            14 => [ // Yam (per tuber)
                ['yam-1.jpg', true, 0],
            ],
            23 => [ // YAM (duplicate product)
                ['yam-1.jpg', true, 0],
            ],
            15 => [ // Fresh Tomatoes (per basket)
                ['fresh-tomatoes-1.jpg', true,  0],
                ['fresh-tomatoes-2.jpg', false, 1],
            ],
            16 => [ // Ugwu Leaves (per bunch)
                ['ugwu-1.jpg', true, 0],
            ],
            17 => [ // Fresh Farm Milk
                ['farm-milk-1.jpg', true,  0],
                ['farm-milk-2.jpg', false, 1],
            ],
            18 => [ // Organic Poultry Compost (25kg)
                ['compost-1.jpg', true,  0],
                ['compost-2.jpg', false, 1],
            ],
            19 => [ // Tomato Seedlings (per tray)
                ['tomato-seedlings-1.jpg', true,  0],
                ['tomato-seedlings-2.jpg', false, 1],
            ],
            20 => [ // Weekly Egg Box
                ['eggs-30-1.jpg', true,  0],
                ['eggs-60-1.jpg', false, 1],
            ],
            21 => [ // Weekly Protein Box
                ['dressed-broiler-1.jpg', true,  0],
                ['eggs-30-1.jpg',         false, 1],
                ['farm-milk-1.jpg',       false, 2],
            ],
            22 => [ // Full Farm Box (Premium)
                ['fresh-maize-1.jpg',    true,  0],
                ['eggs-30-1.jpg',        false, 1],
                ['dressed-broiler-1.jpg', false, 2],
                ['farm-milk-1.jpg',      false, 3],
            ],
        ];

        $rows = [];
        $now  = now();

        foreach ($map as $productId => $images) {
            foreach ($images as [$file, $isPrimary, $sort]) {
                $rows[] = [
                    'product_id' => $productId,
                    'url'        => 'products/' . $file,
                    'alt_text'   => null,
                    'is_primary' => $isPrimary,
                    'sort_order' => $sort,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        ProductImage::insert($rows);
    }
}
