<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Poultry',
                'slug'        => 'poultry',
                'description' => 'Fresh broiler chickens, turkeys, and ducks raised on our 50-acre farm. All birds are free-range and naturally fed.',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Eggs',
                'slug'        => 'eggs',
                'description' => 'Farm-fresh table eggs, fertilized hatching eggs, and quail eggs. Collected daily from our healthy hens.',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Livestock',
                'slug'        => 'livestock',
                'description' => 'Live goats, rams, and pigs sourced from our certified livestock farm. Available live or dressed.',
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Crop Produce',
                'slug'        => 'crop-produce',
                'description' => 'Freshly harvested maize, cassava, yam, tomatoes, peppers, and vegetables direct from our farmland.',
                'sort_order'  => 4,
            ],
            [
                'name'        => 'Dairy',
                'slug'        => 'dairy',
                'description' => 'Pure, unprocessed farm milk and natural yogurt from our grass-fed dairy animals.',
                'sort_order'  => 5,
            ],
            [
                'name'        => 'Organic Inputs',
                'slug'        => 'organic-inputs',
                'description' => 'High-quality organic poultry compost and vegetable seedlings to boost your farm productivity.',
                'sort_order'  => 6,
            ],
            [
                'name'        => 'Farm Subscription Boxes',
                'slug'        => 'farm-boxes',
                'description' => 'Weekly curated boxes of fresh farm produce delivered to your door. Save more with a subscription.',
                'sort_order'  => 7,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
