<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FakeListingsSeeder extends Seeder
{
    public function run(): void
    {
        // Create 6 fake users
        $users = [
            ['name' => 'Giorgi Beridze',    'email' => 'giorgi.b@example.com'],
            ['name' => 'Nino Kvaratskhelia','email' => 'nino.k@example.com'],
            ['name' => 'Luka Janelidze',    'email' => 'luka.j@example.com'],
            ['name' => 'Mariam Tsereteli',  'email' => 'mariam.t@example.com'],
            ['name' => 'Davit Goglidze',    'email' => 'davit.g@example.com'],
            ['name' => 'Ana Mchedlishvili', 'email' => 'ana.m@example.com'],
            ['name' => 'Tornike Basilaia',  'email' => 'tornike.b@example.com'],
            ['name' => 'Salome Kirtadze',   'email' => 'salome.k@example.com'],
            ['name' => 'Irakli Dgebuadze',  'email' => 'irakli.d@example.com'],
        ];

        $createdUsers = [];
        foreach ($users as $u) {
            $createdUsers[] = User::firstOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'password' => Hash::make('password123')]
            );
        }

        $listings = [
            [
                'name'        => 'Sony PlayStation 5',
                'description' => 'PS5 disk edition, used for 8 months. Comes with 2 controllers and 3 games (FIFA 24, God of War Ragnarök, Spider-Man 2). Everything in perfect working condition. No scratches, original box included.',
                'category'    => 'gaming',
                'sub_category'=> 'consoles',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'MacBook Pro 14" M2',
                'description' => 'MacBook Pro 14-inch, M2 Pro chip, 16GB RAM, 512GB SSD. Space Gray. Purchased in 2023, used lightly for work. Battery cycles under 80. Comes with original charger and box.',
                'category'    => 'electronics',
                'sub_category'=> 'laptops',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'mobiles',
            ],
            [
                'name'        => 'iPhone 13 Pro 256GB',
                'description' => 'iPhone 13 Pro, Sierra Blue, 256GB. Face ID works perfectly, battery health 89%. Minor scratches on the back, screen is pristine. Comes with cable only.',
                'category'    => 'mobiles',
                'sub_category'=> 'smartphones',
                'condition'   => 'Good',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Canon EOS R50 Camera Kit',
                'description' => 'Canon EOS R50 mirrorless camera with 18-45mm kit lens. Only 1,200 shutter actuations. Perfect for photography beginners and enthusiasts. Includes original bag, 2 batteries and 64GB card.',
                'category'    => 'electronics',
                'sub_category'=> 'photography',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Nike Air Jordan 1 Retro High OG',
                'description' => 'Air Jordan 1 Retro High OG "Chicago" colorway. Size EU 43 / US 9.5. Worn twice, in excellent condition. Original box and extra laces included. Authentic, purchased from Nike store.',
                'category'    => 'fashion',
                'sub_category'=> 'shoes',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'fashion',
            ],
            [
                'name'        => 'DJI Mini 3 Drone',
                'description' => 'DJI Mini 3 with RC-N1 remote controller. Under 249g, no registration needed. 3 batteries included, total flight time ~100 minutes. Comes in original carry bag. No crashes or repairs.',
                'category'    => 'electronics',
                'sub_category'=> 'photography',
                'condition'   => 'Like New',
                'location'    => 'Kutaisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Yamaha FG800 Acoustic Guitar',
                'description' => 'Yamaha FG800 full-size acoustic guitar in natural finish. Bought 1 year ago, played occasionally. Great tone, no cracks or damage. Comes with soft case and extra strings set.',
                'category'    => 'music',
                'sub_category'=> 'guitars',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Samsung 32" 4K Gaming Monitor',
                'description' => 'Samsung Odyssey G7 32-inch curved gaming monitor, 4K 144Hz, 1ms response time. HDMI 2.1 and DisplayPort. Used for 10 months, zero dead pixels. Original stand and box included.',
                'category'    => 'electronics',
                'sub_category'=> 'laptops',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'gaming',
            ],
            [
                'name'        => 'Adidas Ultraboost 22 Running Shoes',
                'description' => 'Adidas Ultraboost 22, Core Black/White, size EU 42. Worn about 10 times for light jogging. Boost sole is still very springy. No visible damage, cleaned and ready to go.',
                'category'    => 'fashion',
                'sub_category'=> 'shoes',
                'condition'   => 'Good',
                'location'    => 'Rustavi, Georgia',
                'looking_for' => 'sports',
            ],
            [
                'name'        => 'iPad Air 5th Gen 64GB WiFi',
                'description' => 'iPad Air 5th generation, 64GB, Space Gray, WiFi only. M1 chip, used for 6 months. No damage, screen is perfect. Comes with Apple USB-C cable and original box.',
                'category'    => 'electronics',
                'sub_category'=> 'laptops',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'mobiles',
            ],
            [
                'name'        => 'Trek Marlin 5 Mountain Bike 2022',
                'description' => 'Trek Marlin 5 hardtail mountain bike, 29", medium frame, hydraulic disc brakes. Ridden on trails about 15 times. Gears shift smoothly. Recently serviced. Frame color: Matte Trek Black.',
                'category'    => 'sports',
                'sub_category'=> 'cycling',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Sony WH-1000XM5 Headphones',
                'description' => 'Sony WH-1000XM5 wireless noise-cancelling headphones in black. Used for 4 months, in pristine condition. ANC is outstanding. Comes with carrying case, cable and original box.',
                'category'    => 'electronics',
                'sub_category'=> 'audio',
                'condition'   => 'Like New',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'mobiles',
            ],
            [
                'name'        => 'IKEA KALLAX Shelf Unit 4x4',
                'description' => 'IKEA KALLAX 4x4 shelf unit in white. 147x147cm. Good condition, minor scuffs on bottom. Self-pickup only in Tbilisi. Currently used as room divider. All fixings included.',
                'category'    => 'home-garden',
                'sub_category'=> 'furniture',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'home-garden',
            ],
            [
                'name'        => 'GoPro Hero 11 Black',
                'description' => 'GoPro Hero 11 Black action camera. Used on 3 ski trips. Comes with 3 adhesive mounts, chest harness, head strap and 2 batteries. All in original case. Footage quality is stunning.',
                'category'    => 'electronics',
                'sub_category'=> 'photography',
                'condition'   => 'Good',
                'location'    => 'Gudauri, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Levi\'s 501 Original Jeans Bundle (3 pairs)',
                'description' => 'Three pairs of Levi\'s 501 original fit jeans: light wash, dark wash, and black. All size W32/L32. Worn occasionally, no fading or damage. Great condition overall.',
                'category'    => 'fashion',
                'sub_category'=> 'mens-clothing',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'fashion',
            ],
            [
                'name'        => 'Kindle Paperwhite 11th Gen',
                'description' => 'Amazon Kindle Paperwhite 11th generation, 8GB, waterproof, black. Comes with leather cover (worth $50). Battery lasts weeks. Only 30 books read on it. Like new condition.',
                'category'    => 'books',
                'sub_category'=> 'fiction',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'books',
            ],
            [
                'name'        => 'Bosch PSB 1800 LI-2 Cordless Drill',
                'description' => 'Bosch PSB 1800 LI-2 cordless combi drill. 18V, 2 batteries included. Used for home renovation projects, still has plenty of life. Comes in original carry case with all bits.',
                'category'    => 'tools',
                'sub_category'=> 'power-tools',
                'condition'   => 'Good',
                'location'    => 'Gori, Georgia',
                'looking_for' => 'tools',
            ],
            [
                'name'        => 'Xbox Series X + 2 Controllers',
                'description' => 'Xbox Series X console with 2 wireless controllers (one standard, one Carbon Black special edition). 1TB storage, all cables included. Used for about a year. In great working condition.',
                'category'    => 'gaming',
                'sub_category'=> 'consoles',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Instant Pot Duo 7-in-1 6L',
                'description' => 'Instant Pot Duo 7-in-1 electric pressure cooker, 6 litre. Used about 20 times. All seals are in great condition. Comes with steam rack, measuring cup and all original accessories.',
                'category'    => 'home-garden',
                'sub_category'=> 'kitchen',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'home-garden',
            ],
            [
                'name'        => 'Samsung Galaxy S23 Ultra 256GB',
                'description' => 'Samsung Galaxy S23 Ultra, Phantom Black, 256GB. Excellent camera system with 200MP. S-Pen works perfectly. Battery health still excellent. Screen protector applied from day one, no scratches.',
                'category'    => 'mobiles',
                'sub_category'=> 'smartphones',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Razer BlackShark V2 Pro Gaming Headset',
                'description' => 'Razer BlackShark V2 Pro wireless gaming headset. 70-hour battery, THX Spatial Audio. Used for 5 months, no issues. Ear cushions are clean, mic quality is excellent.',
                'category'    => 'gaming',
                'sub_category'=> 'accessories',
                'condition'   => 'Like New',
                'location'    => 'Kutaisi, Georgia',
                'looking_for' => 'gaming',
            ],
            [
                'name'        => 'North Face Puffer Jacket XL',
                'description' => 'The North Face 700-fill down jacket in navy blue, size XL men\'s. Worn one winter season. Zipper and pockets work fine, no tears or stains. Warm and lightweight.',
                'category'    => 'fashion',
                'sub_category'=> 'mens-clothing',
                'condition'   => 'Good',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'fashion',
            ],
            [
                'name'        => 'Vitamix E310 Explorian Blender',
                'description' => 'Vitamix E310 Explorian blender, black, 1.4 litre container. Motor is powerful and quiet. Blends everything perfectly. Used regularly for 1 year. Comes with tamper and recipe book.',
                'category'    => 'home-garden',
                'sub_category'=> 'kitchen',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'home-garden',
            ],
            [
                'name'        => 'Mechanical Keyboard Keychron K2 v2',
                'description' => 'Keychron K2 v2 wireless mechanical keyboard with Gateron Brown switches. RGB backlight, aluminum frame. Works on Mac and Windows. Used for 6 months, great condition.',
                'category'    => 'electronics',
                'sub_category'=> 'laptops',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Wilson Pro Staff Tennis Racket',
                'description' => 'Wilson Pro Staff 97 v13 tennis racket, grip size 3 (4 3/8"). Used for one season of club play. Freshly restrung with Luxilon ALU Power. Comes with original cover bag.',
                'category'    => 'sports',
                'sub_category'=> 'outdoor-sports',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'sports',
            ],
        ];

        foreach ($listings as $i => $data) {
            $user = $createdUsers[$i % count($createdUsers)];

            Product::create([
                'user_id'      => $user->id,
                'name'         => $data['name'],
                'description'  => $data['description'],
                'category'     => $data['category'],
                'sub_category' => $data['sub_category'],
                'condition'    => $data['condition'],
                'location'     => $data['location'],
                'looking_for'  => $data['looking_for'],
                'hide'         => 0,
                'image_paths'  => json_encode([]),
            ]);
        }

        $this->command->info('Created ' . count($listings) . ' fake listings across ' . count($createdUsers) . ' users.');
    }
}
