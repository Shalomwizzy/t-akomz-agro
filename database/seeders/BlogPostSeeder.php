<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title'       => '5 Reasons Why Farm-Fresh Eggs Are Better Than Store-Bought',
                'excerpt'     => 'Discover the nutritional and quality differences between fresh farm eggs and supermarket eggs. Your health will thank you.',
                'content'     => '<h2>The Farm Fresh Difference</h2><p>When it comes to eggs, not all are created equal. Farm-fresh eggs from free-range hens offer significantly better nutrition, taste, and quality compared to their supermarket counterparts.</p><h3>1. Superior Nutritional Profile</h3><p>Studies show that eggs from free-range hens contain up to 2x more vitamin E, 3x more omega-3 fatty acids, and 7x more beta carotene than conventionally raised eggs. At T-Akomz, our hens roam freely and eat a natural diet, which directly translates to better-quality eggs.</p><h3>2. Fresher, Better Taste</h3><p>Commercial eggs can sit in cold storage for weeks before reaching shelves. Our eggs are collected daily and delivered within 24–48 hours. You can literally taste the difference in the richness of the yolk and the firmness of the white.</p><h3>3. Deeper, More Vibrant Yolks</h3><p>Notice how our eggs have deep orange-yellow yolks? That color comes from natural carotenoids in the hens\' diet — grasses, insects, and natural grains. Pale yolks are a sign of confined, poorly fed birds.</p><h3>4. No Hidden Chemicals</h3><p>Our hens are raised without antibiotics, growth hormones, or artificial feed additives. What goes into the hen directly affects what comes out in the egg.</p><h3>5. You Know Where They Come From</h3><p>When you buy from T-Akomz Agro Estates, you know exactly which farm your eggs came from. Transparency and traceability are things supermarket supply chains simply cannot offer.</p><p>Ready to experience the difference? <a href="/shop/eggs">Order fresh farm eggs today</a> and get them delivered to your door.</p>',
                'author_name' => 'T-Akomz Farm Team',
                'category'    => 'Nutrition',
                'tags'        => ['eggs', 'nutrition', 'farm fresh', 'health'],
                'is_published' => true,
            ],
            [
                'title'       => 'How to Raise Broiler Chickens: A Beginner\'s Complete Guide',
                'excerpt'     => 'Thinking of starting a poultry farm? This step-by-step guide covers everything from brooder setup to feed management and disease prevention.',
                'content'     => '<h2>Getting Started with Broiler Farming</h2><p>Broiler farming is one of the most profitable ventures in Nigerian agriculture. With the right setup and management, you can raise a batch of chickens from day-old chicks to market weight in just 6–8 weeks.</p><h3>Step 1: Prepare Your Brooder House</h3><p>Before your chicks arrive, you need a warm, draft-free brooder. Use wood shavings as litter (4–6 inches deep), set up heat lamps to maintain 32–35°C for the first week, and ensure adequate ventilation without direct drafts.</p><h3>Step 2: Choose Quality Day-Old Chicks</h3><p>Source your chicks from reputable hatcheries or farms like T-Akomz Agro Estates. Quality indicators: active, alert, uniform in size, no pasty vents, bright eyes. Vaccinated against Marek\'s disease.</p><h3>Step 3: Feeding Schedule</h3><p><strong>Weeks 1–3:</strong> Broiler starter feed (22–24% protein)<br><strong>Weeks 3–6:</strong> Broiler grower (20–22% protein)<br><strong>Final week:</strong> Broiler finisher (18–20% protein, no antibiotics if selling fresh)</p><h3>Step 4: Water Management</h3><p>Fresh, clean water must be available at all times. Chicks drink 2x more water than feed. Medicate water as per veterinary advice during disease outbreaks.</p><h3>Step 5: Disease Prevention</h3><p>Vaccinate against Newcastle, Gumboro, and Fowl Typhoid at appropriate ages. Maintain strict biosecurity — visitors should dip footwear in disinfectant before entering the farm.</p><h3>Common Mistakes to Avoid</h3><ul><li>Overcrowding (allow 0.1 sqm per bird minimum)</li><li>Ignoring ventilation</li><li>Inconsistent feeding times</li><li>Not culling sick birds early</li></ul><p>Need quality day-old chicks to get started? <a href="/shop/poultry">Order from T-Akomz today</a>.</p>',
                'author_name' => 'T-Akomz Farm Team',
                'category'    => 'Farming Tips',
                'tags'        => ['broiler', 'poultry farming', 'guide', 'beginners'],
                'is_published' => true,
            ],
            [
                'title'       => 'Why Organic Farming is the Future of Agriculture in Nigeria',
                'excerpt'     => 'As consumer awareness grows and export opportunities expand, organic farming is becoming not just an ethical choice but a profitable one.',
                'content'     => '<h2>The Organic Agriculture Opportunity in Nigeria</h2><p>Nigeria\'s agricultural sector is at an inflection point. With growing consumer awareness about food safety, expanding export opportunities, and increasing government support, organic farming has never been more promising.</p><h3>What Does "Organic" Really Mean?</h3><p>Organic farming means growing and raising food without synthetic pesticides, artificial fertilizers, growth hormones, or antibiotics. It relies on natural processes, crop rotation, composting, and biological pest control.</p><h3>Why Nigerian Farmers Should Transition</h3><p><strong>Premium Prices:</strong> Organic produce commands 30–50% higher prices in Nigerian urban markets and significantly more in export markets (EU, US, UK).</p><p><strong>Export Potential:</strong> Certified organic produce from Nigeria can access lucrative European and American markets. Several Nigerian organic farms are already exporting sesame, cocoa, ginger, and cassava.</p><p><strong>Soil Health:</strong> Continuous use of synthetic fertilizers degrades soil over time. Organic practices rebuild soil biology, making farms more productive in the long run.</p><h3>T-Akomz\'s Organic Journey</h3><p>Since 2018, T-Akomz Agro Estates has operated on organic principles — no antibiotics in our poultry, no synthetic fertilizers on our crops, and no artificial feed additives. Our organic poultry compost is one of our bestselling products because farmers see the difference it makes.</p><h3>Getting Started</h3><p>Transitioning to organic doesn\'t happen overnight. Start with one aspect — eliminate antibiotics from your poultry, or switch one crop plot to organic methods. Build knowledge and confidence gradually.</p><p>Our <a href="/shop/organic-inputs">organic inputs</a> — compost and seedlings — are available for delivery.</p>',
                'author_name' => 'T-Akomz Farm Team',
                'category'    => 'Sustainability',
                'tags'        => ['organic', 'sustainable', 'farming', 'Nigeria', 'export'],
                'is_published' => true,
            ],
        ];

        foreach ($posts as $data) {
            $slug = Str::slug($data['title']);
            BlogPost::updateOrCreate(
                ['slug' => $slug],
                [
                    'title'        => $data['title'],
                    'slug'         => $slug,
                    'excerpt'      => $data['excerpt'],
                    'content'      => $data['content'],
                    'author_name'  => $data['author_name'],
                    'category'     => $data['category'],
                    'tags'         => $data['tags'],
                    'is_published' => $data['is_published'],
                    'published_at' => now()->subDays(rand(1, 30)),
                ]
            );
        }
    }
}
