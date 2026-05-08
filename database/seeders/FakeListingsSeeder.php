<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FakeListingsSeeder extends Seeder
{
    // Emails used by this seeder — safe to delete on re-run
    private array $seederEmails = [
        'alex92.swap@example.com',
        'miketrader@example.com',
        'swap.king99@example.com',
        'liu.wei.trades@example.com',
        'pedro_mx@example.com',
        'emma_london@example.com',
        'techdealer2001@example.com',
        'kasia.pl@example.com',
        'raj_swaps@example.com',
    ];

    public function run(): void
    {
        // ── 1. Delete previous seeder data ───────────────────────
        $oldIds = User::whereIn('email', $this->seederEmails)->pluck('id');
        if ($oldIds->isNotEmpty()) {
            Product::whereIn('user_id', $oldIds)->delete();
            User::whereIn('id', $oldIds)->delete();
            $this->command->info('Deleted ' . $oldIds->count() . ' old seeder users and their products.');
        }

        // ── 2. Create users ───────────────────────────────────────
        $usersData = [
            ['name' => 'alex_92',         'email' => 'alex92.swap@example.com'],
            ['name' => 'MikeTrader',      'email' => 'miketrader@example.com'],
            ['name' => 'swap_king99',     'email' => 'swap.king99@example.com'],
            ['name' => 'liu.wei',         'email' => 'liu.wei.trades@example.com'],
            ['name' => 'pedro_mx',        'email' => 'pedro_mx@example.com'],
            ['name' => 'emma_london',     'email' => 'emma_london@example.com'],
            ['name' => 'TechDealer2001',  'email' => 'techdealer2001@example.com'],
            ['name' => 'kasia.pl',        'email' => 'kasia.pl@example.com'],
            ['name' => 'raj_swaps',       'email' => 'raj_swaps@example.com'],
        ];

        $users = [];
        foreach ($usersData as $u) {
            $users[] = User::create([
                'name'     => $u['name'],
                'email'    => $u['email'],
                'password' => Hash::make('password123'),
            ]);
        }

        // ── 3. Photo helper — picsum.photos with seed for consistent images ──
        // 400x300 matches card display size, seed keeps same image on every seeder run
        $photo = fn(string $seed) =>
            "https://picsum.photos/seed/{$seed}/400/300";

        // ── 4. Listings ───────────────────────────────────────────
        $listings = [
            [
                'name'        => 'Redmi Note 11 64GB',
                'description' => 'Xiaomi Redmi Note 11, 64GB, Graphite Gray. Works perfectly, minor scratches on back. Battery lasts all day. Comes with charger and silicone case.',
                'category'    => 'mobiles',
                'sub_category'=> 'smartphones',
                'condition'   => 'Good',
                'location'    => 'Warsaw, Poland',
                'looking_for' => 'electronics',
                'photos'      => [$photo('smartphone11'), $photo('android12')],
            ],
            [
                'name'        => 'JBL Clip 4 Bluetooth Speaker',
                'description' => 'JBL Clip 4 portable bluetooth speaker in blue. Waterproof, great sound for its size. Used outdoors a few times. Battery still holds charge well. Comes with USB-C cable.',
                'category'    => 'electronics',
                'sub_category'=> 'audio',
                'condition'   => 'Good',
                'location'    => 'Berlin, Germany',
                'looking_for' => 'electronics',
                'photos'      => [$photo('speaker21'), $photo('audio22')],
            ],
            [
                'name'        => 'Complete Skateboard Setup',
                'description' => 'Complete skateboard with 8" deck, Tensor trucks and 52mm wheels. Ridden for one summer, bearings still smooth. Good for beginners and intermediate riders.',
                'category'    => 'sports',
                'sub_category'=> 'outdoor-sports',
                'condition'   => 'Good',
                'location'    => 'Barcelona, Spain',
                'looking_for' => 'sports',
                'photos'      => [$photo('skateboard31'), $photo('skate32')],
            ],
            [
                'name'        => 'IKEA FORSÅ Desk Lamp',
                'description' => 'IKEA FORSÅ desk lamp in white. Works perfectly, adjustable arm. E14 bulb included. Used for 2 years on a study desk. Minor scratch on base, otherwise fine.',
                'category'    => 'home-garden',
                'sub_category'=> 'lighting',
                'condition'   => 'Good',
                'location'    => 'Amsterdam, Netherlands',
                'looking_for' => 'home-garden',
                'photos'      => [$photo('lamp41'), $photo('interior42')],
            ],
            [
                'name'        => 'Harry Potter Book Set (1–7)',
                'description' => 'Complete Harry Potter series, books 1 through 7, English paperback. All books read once, no torn pages or writing inside. Great condition overall.',
                'category'    => 'books',
                'sub_category'=> 'fiction',
                'condition'   => 'Good',
                'location'    => 'London, UK',
                'looking_for' => 'books',
                'photos'      => [$photo('books51'), $photo('reading52')],
            ],
            [
                'name'        => 'Soprano Ukulele (Mahogany)',
                'description' => 'Soprano ukulele in mahogany finish, 21 inch. Bought as a hobby but rarely played. Comes with soft carry bag and spare strings. Tuning pegs work fine, good sound.',
                'category'    => 'music',
                'sub_category'=> 'guitars',
                'condition'   => 'Like New',
                'location'    => 'Lisbon, Portugal',
                'looking_for' => 'music',
                'photos'      => [$photo('ukulele61'), $photo('music62')],
            ],
            [
                'name'        => 'Levi\'s Denim Jacket Size M',
                'description' => 'Levi\'s classic trucker denim jacket, medium wash, size M. Worn a handful of times, no fading or damage. Buttons all intact. Great layering piece.',
                'category'    => 'fashion',
                'sub_category'=> 'mens-clothing',
                'condition'   => 'Good',
                'location'    => 'Rome, Italy',
                'looking_for' => 'fashion',
                'photos'      => [$photo('jacket71'), $photo('fashion72')],
            ],
            [
                'name'        => 'Yoga Mat + Resistance Bands Set',
                'description' => 'Non-slip 6mm yoga mat in purple with carry strap, plus 5 resistance bands (different levels). Used at home for 3 months. Clean and in good condition.',
                'category'    => 'sports',
                'sub_category'=> 'fitness',
                'condition'   => 'Good',
                'location'    => 'Paris, France',
                'looking_for' => 'sports',
                'photos'      => [$photo('yoga81'), $photo('fitness82')],
            ],
            [
                'name'        => 'Logitech G102 Gaming Mouse',
                'description' => 'Logitech G102 LIGHTSYNC wired gaming mouse, black. 8000 DPI, RGB lighting. Used for 1 year, sensor works flawlessly. Cable intact with no kinks.',
                'category'    => 'gaming',
                'sub_category'=> 'accessories',
                'condition'   => 'Good',
                'location'    => 'Mumbai, India',
                'looking_for' => 'gaming',
                'photos'      => [$photo('mouse91'), $photo('gaming92')],
            ],
            [
                'name'        => 'Russell Hobbs Electric Kettle',
                'description' => 'Russell Hobbs 1.7L electric kettle in black. Fast boil, auto shut-off. Used daily for 1.5 years, no limescale. Works perfectly, cord intact.',
                'category'    => 'home-garden',
                'sub_category'=> 'kitchen',
                'condition'   => 'Good',
                'location'    => 'Manchester, UK',
                'looking_for' => 'home-garden',
                'photos'      => [$photo('kettle101'), $photo('kitchen102')],
            ],
            [
                'name'        => 'Converse Chuck Taylor High Top EU 41',
                'description' => 'Converse Chuck Taylor All Star high tops in black canvas, EU 41. Worn maybe 10 times. Sole clean, canvas has no tears. Original box included.',
                'category'    => 'fashion',
                'sub_category'=> 'shoes',
                'condition'   => 'Like New',
                'location'    => 'Istanbul, Turkey',
                'looking_for' => 'fashion',
                'photos'      => [$photo('sneakers111'), $photo('shoes112')],
            ],
            [
                'name'        => 'Osprey Daylite 13L Backpack',
                'description' => 'Osprey Daylite 13L daypack in black. Used for city commuting for one semester. No tears or broken zips. Laptop sleeve fits up to 13".',
                'category'    => 'fashion',
                'sub_category'=> 'bags',
                'condition'   => 'Good',
                'location'    => 'Prague, Czech Republic',
                'looking_for' => 'fashion',
                'photos'      => [$photo('backpack121'), $photo('travel122')],
            ],
            [
                'name'        => 'PS4 DualShock 4 Controller (Black)',
                'description' => 'Sony DualShock 4 controller for PS4, jet black. Thumbsticks have minor wear but work correctly. All buttons and triggers function normally. USB cable included.',
                'category'    => 'gaming',
                'sub_category'=> 'accessories',
                'condition'   => 'Fair',
                'location'    => 'Kyiv, Ukraine',
                'looking_for' => 'gaming',
                'photos'      => [$photo('gamepad131'), $photo('controller132')],
            ],
            [
                'name'        => 'Potted Snake Plant (Sansevieria)',
                'description' => 'Healthy snake plant, about 50cm tall, in a terracotta pot. Very low maintenance. Self-pickup only. Great for apartments with low light.',
                'category'    => 'home-garden',
                'sub_category'=> 'garden',
                'condition'   => 'Good',
                'location'    => 'Vienna, Austria',
                'looking_for' => 'home-garden',
                'photos'      => [$photo('plant141'), $photo('nature142')],
            ],
            [
                'name'        => 'Xiaomi Mi Band 6 Fitness Tracker',
                'description' => 'Mi Band 6, black strap. Tracks heart rate, sleep and steps. Screen no scratches. Battery lasts ~2 weeks. Comes with charger.',
                'category'    => 'electronics',
                'sub_category'=> 'wearables',
                'condition'   => 'Good',
                'location'    => 'Shanghai, China',
                'looking_for' => 'electronics',
                'photos'      => [$photo('watch151'), $photo('tracker152')],
            ],
            [
                'name'        => 'Wacom Intuos S Drawing Tablet',
                'description' => 'Wacom Intuos Small (CTL-4100) drawing tablet in black. Works on Windows and Mac. Used for digital art. Pen and USB cable included.',
                'category'    => 'art',
                'sub_category'=> 'digital-art',
                'condition'   => 'Good',
                'location'    => 'Seoul, South Korea',
                'looking_for' => 'electronics',
                'photos'      => [$photo('tablet161'), $photo('drawing162')],
            ],
            [
                'name'        => 'Waterproof Winter Boots EU 39',
                'description' => 'Warm waterproof winter boots, black, EU 39. Worn for one winter. Soles have good grip. Inner lining clean and intact. Zip works fine.',
                'category'    => 'fashion',
                'sub_category'=> 'shoes',
                'condition'   => 'Good',
                'location'    => 'Stockholm, Sweden',
                'looking_for' => 'fashion',
                'photos'      => [$photo('boots171'), $photo('winter172')],
            ],
            [
                'name'        => 'Catan Board Game (English)',
                'description' => 'Catan base game, English edition. All pieces present and in great condition. Played ~15 times, cards are unmarked. Box has minor shelf wear.',
                'category'    => 'toys',
                'sub_category'=> 'board-games',
                'condition'   => 'Good',
                'location'    => 'Toronto, Canada',
                'looking_for' => 'toys',
                'photos'      => [$photo('boardgame181'), $photo('tabletop182')],
            ],
            [
                'name'        => 'Romoss 10000mAh Power Bank',
                'description' => 'Romoss 10000mAh portable power bank, black. Dual USB, charges phone 2-3 times. Used for travel. Works well, original pouch included.',
                'category'    => 'electronics',
                'sub_category'=> 'phones',
                'condition'   => 'Good',
                'location'    => 'Dubai, UAE',
                'looking_for' => 'electronics',
                'photos'      => [$photo('charger191'), $photo('powerbank192')],
            ],
            [
                'name'        => 'Nike Downshifter 11 Running Shoes EU 44',
                'description' => 'Nike Downshifter 11 in black/white, EU 44. Used 3 months of casual jogging. Cushioning still good, no holes or tears. Clean and odour-free.',
                'category'    => 'sports',
                'sub_category'=> 'fitness',
                'condition'   => 'Good',
                'location'    => 'São Paulo, Brazil',
                'looking_for' => 'sports',
                'photos'      => [$photo('running201'), $photo('sport202')],
            ],
            [
                'name'        => 'Wooden Desk Organiser + Stationery',
                'description' => 'Wooden desk organiser with 5 compartments plus pens, highlighters and sticky notes. Used on a home desk for one year. Clean and practical. All pens work.',
                'category'    => 'office',
                'sub_category'=> 'stationery',
                'condition'   => 'Good',
                'location'    => 'New York, USA',
                'looking_for' => 'office',
                'photos'      => [$photo('desk211'), $photo('office212')],
            ],
            [
                'name'        => 'Ergobaby Omni 360 Baby Carrier',
                'description' => 'Ergobaby Omni 360 baby carrier in grey. Used ~6 months, washed and clean. All buckles and straps work. Suitable from newborn to toddler (up to 20kg).',
                'category'    => 'baby',
                'sub_category'=> 'baby-clothes',
                'condition'   => 'Good',
                'location'    => 'Sydney, Australia',
                'looking_for' => 'baby',
                'photos'      => [$photo('baby221'), $photo('newborn222')],
            ],
            [
                'name'        => 'Philips Series 3000 Electric Shaver',
                'description' => 'Philips Series 3000 dry electric shaver. Used 8 months, blades still sharp. Comes with cleaning brush and charging cable. Works well on both short and longer stubble.',
                'category'    => 'beauty',
                'sub_category'=> 'grooming',
                'condition'   => 'Good',
                'location'    => 'Cairo, Egypt',
                'looking_for' => 'electronics',
                'photos'      => [$photo('shaver231'), $photo('grooming232')],
            ],
            [
                'name'        => 'Cotton Hammock with Hanging Kit',
                'description' => 'Cotton woven hammock, blue and white stripes, 200x150cm. Comes with hanging straps, carabiners and carry pouch. Used two summers. Still colourful and strong.',
                'category'    => 'home-garden',
                'sub_category'=> 'garden',
                'condition'   => 'Good',
                'location'    => 'Mexico City, Mexico',
                'looking_for' => 'home-garden',
                'photos'      => [$photo('hammock241'), $photo('garden242')],
            ],
            [
                'name'        => 'Cat Bed + Toy Bundle',
                'description' => 'Round fluffy cat bed (50cm) in grey plus 6 cat toys (feather wands, balls, mouse). Bed is washed and clean. Toys barely used — my cat preferred the cardboard box.',
                'category'    => 'pets',
                'sub_category'=> 'pet-supplies',
                'condition'   => 'Like New',
                'location'    => 'Tokyo, Japan',
                'looking_for' => 'pets',
                'photos'      => [$photo('cat251'), $photo('kitten252')],
            ],
        ];

        // ── 5. Insert products ────────────────────────────────────
        foreach ($listings as $i => $data) {
            $user = $users[$i % count($users)];

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
                'image_paths'  => json_encode($data['photos']),
            ]);
        }

        $this->command->info('✓ Created ' . count($users) . ' users and ' . count($listings) . ' listings.');
    }
}
