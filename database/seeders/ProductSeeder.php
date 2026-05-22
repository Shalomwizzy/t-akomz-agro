<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ─── POULTRY ──────────────────────────────────────────────────
            [
                'category' => 'poultry',
                'name'     => 'Live Broiler Chicken',
                'price'    => 5500.00,
                'compare_price' => 6500.00,
                'unit'     => 'per bird',
                'stock'    => 200,
                'is_featured' => true,
                'is_organic'  => true,
                'short_description' => 'Farm-raised broiler chicken, approximately 2.5kg live weight. Naturally fed, no antibiotics.',
                'description' => '<p>Our broiler chickens are raised on our 50-acre farm with natural feed and no antibiotics. Each bird averages 2.5kg live weight and is available for pickup or delivery.</p><ul><li>Average weight: 2.0–3.0kg</li><li>Natural grain feed</li><li>Antibiotic-free</li><li>Free-range environment</li></ul>',
                'tags'   => ['chicken', 'poultry', 'fresh', 'live'],
                'images' => ['live-broiler-1.jpg', 'live-broiler-2.jpg'],
            ],
            [
                'category' => 'poultry',
                'name'     => 'Dressed Broiler Chicken',
                'price'    => 4500.00,
                'unit'     => 'per kg',
                'stock'    => 150,
                'is_featured' => true,
                'is_organic'  => true,
                'short_description' => 'Freshly dressed broiler chicken, cleaned and ready to cook. Sold per kg.',
                'description' => '<p>Freshly dressed and cleaned broiler chicken from our farm. No preservatives, no hormones. Dressed same day as delivery.</p><ul><li>Freshly dressed daily</li><li>No preservatives</li><li>Cleaned and ready to cook</li><li>Minimum order: 1kg</li></ul>',
                'tags'   => ['chicken', 'dressed', 'fresh', 'ready-to-cook'],
                'images' => ['dressed-broiler-1.jpg', 'dressed-broiler-2.jpg'],
            ],
            [
                'category' => 'poultry',
                'name'     => 'Point-of-Lay Pullets',
                'price'    => 7500.00,
                'unit'     => 'per bird',
                'stock'    => 80,
                'is_featured' => false,
                'short_description' => '18-week old pullets ready to start laying. Perfect for home or commercial egg production.',
                'description' => '<p>Our point-of-lay pullets are healthy, vaccinated, and ready to begin egg production. At 18–20 weeks, they will start laying within 2–4 weeks of purchase.</p><ul><li>Age: 18–20 weeks</li><li>Fully vaccinated</li><li>Newcastle and Gumboro disease protected</li><li>Expected laying rate: 90%+</li></ul>',
                'tags'   => ['pullets', 'layers', 'egg-production'],
                'images' => ['pullets-1.jpg'],
            ],
            [
                'category' => 'poultry',
                'name'     => 'Day-Old Chicks (Broiler)',
                'price'    => 650.00,
                'unit'     => 'per chick',
                'stock'    => 500,
                'is_featured' => false,
                'short_description' => 'Healthy day-old broiler chicks from reputable hatchery. Minimum order: 25 chicks.',
                'description' => "<p>High-quality day-old broiler chicks sourced from certified hatcheries. Perfect for commercial poultry farming.</p><ul><li>Marek's disease vaccinated</li><li>Minimum order: 25 chicks</li><li>Fast growth rate</li><li>8-week maturity</li></ul>",
                'tags'   => ['chicks', 'day-old', 'broiler'],
                'images' => ['day-old-chicks-1.jpg', 'day-old-chicks-2.jpg'],
            ],
            [
                'category' => 'poultry',
                'name'     => 'Dressed Turkey',
                'price'    => 18000.00,
                'unit'     => 'per bird',
                'stock'    => 30,
                'is_featured' => true,
                'is_organic'  => true,
                'short_description' => 'Dressed whole turkey, approximately 6–8kg. Perfect for celebrations and events.',
                'description' => '<p>Premium farm-raised turkeys, dressed and ready for your celebration. Each bird weighs approximately 6–8kg cleaned.</p><ul><li>Weight: 6–8kg dressed</li><li>Free-range raised</li><li>Natural feed only</li><li>Available fresh or frozen</li></ul>',
                'tags'   => ['turkey', 'celebration', 'premium'],
                'images' => ['dressed-turkey-1.jpg', 'dressed-turkey-2.jpg'],
            ],

            // ─── EGGS ─────────────────────────────────────────────────────
            [
                'category' => 'eggs',
                'name'     => 'Table Eggs - Small Crate (30 eggs)',
                'price'    => 3500.00,
                'compare_price' => 4000.00,
                'unit'     => 'per crate',
                'stock'    => 300,
                'is_featured' => true,
                'is_organic'  => true,
                'short_description' => 'Fresh farm eggs, 30-count small crate. Collected daily from our healthy hens.',
                'description' => '<p>Fresh table eggs collected daily from our free-range hens. Each crate contains 30 eggs. Our hens are fed a natural diet with no growth hormones.</p><ul><li>30 eggs per crate</li><li>Collected fresh daily</li><li>Free-range hens</li><li>No artificial hormones</li></ul>',
                'tags'   => ['eggs', 'fresh', 'table eggs', 'crate'],
                'images' => ['eggs-30-1.jpg', 'eggs-30-2.jpg', 'eggs-30-3.jpg', 'eggs-30-4.jpg'],
            ],
            [
                'category' => 'eggs',
                'name'     => 'Table Eggs - Large Crate (60 eggs)',
                'price'    => 6500.00,
                'compare_price' => 7500.00,
                'unit'     => 'per crate',
                'stock'    => 150,
                'is_featured' => true,
                'is_organic'  => true,
                'short_description' => 'Fresh farm eggs, 60-count large crate. Great value for families and businesses.',
                'description' => '<p>Economy crate of 60 fresh farm eggs. Perfect for families, restaurants, and food businesses. Same quality as our small crates but at a better price per egg.</p><ul><li>60 eggs per crate</li><li>Better price per egg</li><li>Collected fresh daily</li><li>Ideal for bulk buyers</li></ul>',
                'tags'   => ['eggs', 'bulk', 'wholesale', 'family'],
                'images' => ['eggs-60-1.jpg', 'eggs-60-2.jpg'],
            ],
            [
                'category' => 'eggs',
                'name'     => 'Fertilized Hatching Eggs',
                'price'    => 500.00,
                'unit'     => 'per dozen',
                'stock'    => 120,
                'is_featured' => false,
                'short_description' => 'Fertile broiler eggs for hatching. High fertility rate, suitable for incubation.',
                'description' => '<p>Fertile broiler eggs with high hatching rate. Ideal for farmers looking to hatch their own chicks. Fertility rate: 90%+.</p><ul><li>12 eggs per dozen</li><li>90%+ fertility rate</li><li>Suitable for incubation</li><li>Must be incubated within 7 days of receipt</li></ul>',
                'tags'   => ['fertilized', 'hatching', 'incubation'],
                'images' => ['hatching-eggs-1.jpg', 'hatching-eggs-2.jpg'],
            ],
            [
                'category' => 'eggs',
                'name'     => 'Quail Eggs (30 count)',
                'price'    => 2000.00,
                'unit'     => 'per pack',
                'stock'    => 80,
                'is_featured' => false,
                'is_organic'  => true,
                'short_description' => 'Fresh quail eggs, 30 per pack. Rich in nutrients, popular for their health benefits.',
                'description' => '<p>Nutritious quail eggs from our quail farm. Known for their rich nutrient profile and delicate flavor. 30 eggs per pack.</p><ul><li>30 eggs per pack</li><li>High in protein and vitamins</li><li>No antibiotics</li><li>Freshly collected</li></ul>',
                'tags'   => ['quail', 'eggs', 'health', 'nutritious'],
                'images' => ['quail-eggs-1.jpg'],
            ],

            // ─── LIVESTOCK ────────────────────────────────────────────────
            [
                'category' => 'livestock',
                'name'     => 'Live Goat (Medium)',
                'price'    => 45000.00,
                'unit'     => 'per head',
                'stock'    => 25,
                'is_featured' => true,
                'short_description' => 'Healthy live goat, 15–20kg live weight. Perfect for celebrations and special occasions.',
                'description' => '<p>Healthy live goats from our certified livestock farm. Medium size: 15–20kg live weight. Suitable for suya, pepper soup, and celebrations.</p><ul><li>Weight: 15–20kg live</li><li>Vaccinated and healthy</li><li>Available for pickup or live delivery</li><li>Can be dressed on request (+₦2,000)</li></ul>',
                'tags'   => ['goat', 'live', 'livestock'],
                'images' => ['live-goat-1.jpg', 'live-goat-2.jpg'],
            ],
            [
                'category' => 'livestock',
                'name'     => 'Live Ram',
                'price'    => 85000.00,
                'unit'     => 'per head',
                'stock'    => 15,
                'is_featured' => true,
                'short_description' => 'Healthy live ram, ideal for Sallah and celebrations. Various sizes available.',
                'description' => '<p>Premium rams for Eid al-Adha (Sallah), naming ceremonies, and other celebrations. All rams are healthy, vaccinated, and well-fed.</p><ul><li>Various sizes and weights available</li><li>Vaccinated against common diseases</li><li>Available for viewing before purchase</li><li>Delivery available</li></ul>',
                'tags'   => ['ram', 'sallah', 'eid', 'celebration'],
                'images' => ['live-ram-1.jpg'],
            ],
            [
                'category' => 'livestock',
                'name'     => 'Dressed Pork (per kg)',
                'price'    => 3500.00,
                'unit'     => 'per kg',
                'stock'    => 60,
                'is_featured' => false,
                'short_description' => 'Fresh dressed pork from our farm. Cleaned and ready. Minimum 2kg order.',
                'description' => '<p>Fresh, farm-raised pork, dressed and cleaned. Available in various cuts. No preservatives.</p><ul><li>Sold per kg (min. 2kg)</li><li>Various cuts available</li><li>Fresh, no preservatives</li><li>Farm-to-table</li></ul>',
                'tags'   => ['pork', 'pig', 'fresh', 'dressed'],
                'images' => ['dressed-pork-1.jpg', 'dressed-pork-2.jpg', 'dressed-pork-3.jpg'],
            ],

            // ─── CROP PRODUCE ─────────────────────────────────────────────
            [
                'category' => 'crop-produce',
                'name'     => 'Fresh Maize (50kg bag)',
                'price'    => 18000.00,
                'unit'     => 'per bag',
                'stock'    => 100,
                'is_featured' => false,
                'is_organic'  => true,
                'short_description' => 'Freshly harvested white maize, 50kg bag. Great for animal feed and human consumption.',
                'description' => '<p>Freshly harvested white maize from our farmland. Suitable for human consumption, poultry feed, and milling.</p><ul><li>Weight: 50kg per bag</li><li>Freshly harvested</li><li>Low moisture content</li><li>No pesticide residue</li></ul>',
                'tags'   => ['maize', 'corn', 'grain', 'crop'],
                'images' => ['fresh-maize-1.jpg', 'fresh-maize-2.jpg'],
            ],
            [
                'category' => 'crop-produce',
                'name'     => 'Yam (per tuber)',
                'price'    => 1500.00,
                'compare_price' => 2000.00,
                'unit'     => 'per tuber',
                'stock'    => 200,
                'is_featured' => true,
                'is_organic'  => true,
                'short_description' => 'Farm-fresh yam tubers, medium to large size. Harvested and delivered fresh.',
                'description' => '<p>Fresh yam tubers from our farmland. Medium to large sizes available. Delivered fresh within 24 hours of harvest.</p><ul><li>Medium to large sizes</li><li>Organic farming practices</li><li>Fresh harvest</li><li>Suitable for pounded yam, yam porridge, etc.</li></ul>',
                'tags'   => ['yam', 'tuber', 'fresh', 'organic'],
                'images' => ['yam-1.jpg'],
            ],
            [
                'category' => 'crop-produce',
                'name'     => 'Fresh Tomatoes (per basket)',
                'price'    => 12000.00,
                'unit'     => 'per basket',
                'stock'    => 50,
                'is_featured' => false,
                'is_organic'  => true,
                'short_description' => 'Fresh ripe tomatoes, one basket (~25kg). Ideal for cooking, sauce, and paste.',
                'description' => '<p>Freshly harvested ripe tomatoes from our vegetable farm. Each basket contains approximately 25kg of premium tomatoes.</p><ul><li>~25kg per basket</li><li>Freshly harvested</li><li>No post-harvest chemicals</li><li>Ideal for cooking and preservation</li></ul>',
                'tags'   => ['tomatoes', 'vegetables', 'fresh'],
                'images' => ['fresh-tomatoes-1.jpg', 'fresh-tomatoes-2.jpg'],
            ],
            [
                'category' => 'crop-produce',
                'name'     => 'Ugwu Leaves (per bunch)',
                'price'    => 500.00,
                'unit'     => 'per bunch',
                'stock'    => 100,
                'is_featured' => false,
                'is_organic'  => true,
                'short_description' => 'Fresh fluted pumpkin leaves (Ugwu), harvested same day. Rich in iron and nutrients.',
                'description' => '<p>Fresh Ugwu (fluted pumpkin leaves) harvested daily from our vegetable beds. Rich in iron, vitamins, and minerals.</p><ul><li>Freshly harvested same day</li><li>No pesticides</li><li>Rich in iron and vitamins</li><li>Ideal for soups and stews</li></ul>',
                'tags'   => ['ugwu', 'vegetables', 'greens', 'organic'],
                'images' => ['ugwu-1.jpg'],
            ],

            // ─── DAIRY ────────────────────────────────────────────────────
            [
                'category' => 'dairy',
                'name'     => 'Fresh Farm Milk',
                'price'    => 1200.00,
                'unit'     => 'per litre',
                'stock'    => 50,
                'is_featured' => false,
                'is_organic'  => true,
                'short_description' => 'Pure, fresh, unprocessed milk from our dairy animals. Collected fresh daily.',
                'description' => '<p>Pure, unprocessed milk from our healthy dairy animals. Collected fresh each morning. No additives, no preservatives.</p><ul><li>Fresh daily collection</li><li>Unprocessed, natural</li><li>High fat content</li><li>Must be refrigerated</li></ul>',
                'tags'   => ['milk', 'dairy', 'fresh', 'natural'],
                'images' => ['farm-milk-1.jpg', 'farm-milk-2.jpg'],
            ],

            // ─── ORGANIC INPUTS ───────────────────────────────────────────
            [
                'category' => 'organic-inputs',
                'name'     => 'Organic Poultry Compost (25kg)',
                'price'    => 3500.00,
                'unit'     => 'per bag',
                'stock'    => 200,
                'is_featured' => false,
                'is_organic'  => true,
                'short_description' => 'Premium organic poultry manure compost. Excellent NPK fertilizer for all crops.',
                'description' => '<p>High-quality organic compost made from our poultry droppings. Rich in nitrogen, phosphorus, and potassium. Perfect for vegetable gardens, crop farms, and home gardening.</p><ul><li>25kg per bag</li><li>High NPK content</li><li>100% organic</li><li>Suitable for all crops</li><li>Improves soil structure</li></ul>',
                'tags'   => ['compost', 'fertilizer', 'organic', 'manure'],
                'images' => ['compost-1.jpg', 'compost-2.jpg'],
            ],
            [
                'category' => 'organic-inputs',
                'name'     => 'Tomato Seedlings (per tray)',
                'price'    => 2500.00,
                'unit'     => 'per tray',
                'stock'    => 80,
                'is_featured' => false,
                'is_organic'  => true,
                'short_description' => 'Healthy tomato seedlings, 50 per tray. 3–4 weeks old, ready for transplanting.',
                'description' => '<p>Healthy tomato seedlings ready for transplanting. Grown under controlled conditions for uniform germination and growth.</p><ul><li>50 seedlings per tray</li><li>3–4 weeks old</li><li>Ready for transplanting</li><li>High-yield variety</li></ul>',
                'tags'   => ['seedlings', 'tomato', 'planting', 'farming'],
                'images' => ['tomato-seedlings-1.jpg', 'tomato-seedlings-2.jpg'],
            ],

            // ─── FARM BOXES ───────────────────────────────────────────────
            [
                'category' => 'farm-boxes',
                'name'     => 'Weekly Egg Box',
                'price'    => 7500.00,
                'compare_price' => 9000.00,
                'unit'     => 'per week',
                'stock'    => 50,
                'is_featured' => true,
                'is_organic'  => true,
                'short_description' => '2 crates of fresh table eggs + seasonal extras delivered weekly. Save 16% vs buying separately.',
                'description' => '<p>Our Weekly Egg Box delivers 2 crates (60 eggs) of fresh farm eggs every week, plus seasonal extras. Subscribe and save!</p><ul><li>2 crates fresh table eggs (60 eggs)</li><li>Seasonal extras included</li><li>Free delivery within Ekiti</li><li>Pause or cancel anytime</li></ul>',
                'tags'   => ['subscription', 'eggs', 'weekly', 'box'],
                'images' => ['eggs-30-1.jpg', 'eggs-60-1.jpg'],
            ],
            [
                'category' => 'farm-boxes',
                'name'     => 'Weekly Protein Box',
                'price'    => 18500.00,
                'compare_price' => 22000.00,
                'unit'     => 'per week',
                'stock'    => 30,
                'is_featured' => true,
                'is_organic'  => true,
                'short_description' => '1 dressed chicken + 1 crate eggs delivered weekly. Premium protein for your family.',
                'description' => '<p>The Protein Box gives your family a full week of premium animal protein: one dressed broiler chicken (1.5–2kg) and one crate of fresh eggs (30 eggs), delivered weekly.</p><ul><li>1 dressed broiler (~1.5–2kg)</li><li>1 crate fresh eggs (30)</li><li>Free delivery within Ekiti</li><li>Save 16% vs individual pricing</li></ul>',
                'tags'   => ['subscription', 'protein', 'chicken', 'eggs', 'weekly'],
                'images' => ['dressed-broiler-1.jpg', 'eggs-30-1.jpg'],
            ],
            [
                'category' => 'farm-boxes',
                'name'     => 'Full Farm Box (Premium)',
                'price'    => 35000.00,
                'compare_price' => 42000.00,
                'unit'     => 'per week',
                'stock'    => 20,
                'is_featured' => true,
                'is_organic'  => true,
                'short_description' => 'Premium weekly box: chicken, eggs, vegetables, and fresh dairy. Everything your household needs.',
                'description' => '<p>Our premium Full Farm Box is a complete weekly household package. Everything fresh, everything organic, delivered to your door.</p><ul><li>1 dressed broiler chicken (~2kg)</li><li>1 large crate eggs (60 eggs)</li><li>Seasonal vegetables assortment</li><li>1 litre fresh farm milk</li><li>Free priority delivery within Ekiti</li><li>Save 17% vs individual pricing</li></ul>',
                'tags'   => ['subscription', 'premium', 'full-box', 'weekly'],
                'images' => ['dressed-broiler-1.jpg', 'eggs-60-1.jpg', 'farm-milk-1.jpg'],
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category'])->first();
            if (!$category) continue;

            $slug = Str::slug($data['name']);
            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id'         => $category->id,
                    'name'                => $data['name'],
                    'slug'                => $slug,
                    'price'               => $data['price'],
                    'compare_price'       => $data['compare_price'] ?? null,
                    'unit'                => $data['unit'],
                    'stock'               => $data['stock'],
                    'low_stock_threshold' => 10,
                    'is_featured'         => $data['is_featured'] ?? false,
                    'is_organic'          => $data['is_organic'] ?? false,
                    'is_active'           => true,
                    'short_description'   => $data['short_description'],
                    'description'         => $data['description'],
                    'tags'                => $data['tags'] ?? [],
                ]
            );

            // Seed images only if none exist yet for this product
            if (!empty($data['images']) && $product->images()->count() === 0) {
                foreach ($data['images'] as $index => $filename) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url'        => 'products/' . $filename,
                        'alt_text'   => $product->name,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }
        }
    }
}
