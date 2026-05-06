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
                'name'        => 'Redmi Note 11 64GB',
                'description' => 'Xiaomi Redmi Note 11, 64GB, Graphite Gray. Works perfectly, minor scratches on back. Battery lasts all day. Comes with charger and case. Great budget phone for everyday use.',
                'category'    => 'mobiles',
                'sub_category'=> 'smartphones',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Bluetooth Speaker JBL Clip 4',
                'description' => 'JBL Clip 4 portable bluetooth speaker in blue. Waterproof, great sound for its size. Used outdoors a few times. Battery still holds charge well. Comes with USB-C cable.',
                'category'    => 'electronics',
                'sub_category'=> 'audio',
                'condition'   => 'Good',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Skateboard Complete Setup',
                'description' => 'Complete skateboard with 8" deck, Tensor trucks and 52mm wheels. Ridden for one summer, bearings still smooth. Good for beginners and intermediate riders. Grip tape is still good.',
                'category'    => 'sports',
                'sub_category'=> 'outdoor-sports',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'sports',
            ],
            [
                'name'        => 'IKEA Desk Lamp (White)',
                'description' => 'IKEA FORSÅ desk lamp in white. Works perfectly, adjustable arm. E14 bulb included. Used for 2 years on a study desk. Small scratch on base, otherwise in good shape.',
                'category'    => 'home-garden',
                'sub_category'=> 'lighting',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'home-garden',
            ],
            [
                'name'        => 'Harry Potter Book Set (1-7)',
                'description' => 'Complete Harry Potter series, books 1 through 7, English paperback edition. All books read once and kept in great condition. No torn pages or writing inside. Great for any age.',
                'category'    => 'books',
                'sub_category'=> 'fiction',
                'condition'   => 'Good',
                'location'    => 'Kutaisi, Georgia',
                'looking_for' => 'books',
            ],
            [
                'name'        => 'Ukulele Soprano (Mahogany)',
                'description' => 'Soprano ukulele in mahogany finish, 21 inch. Bought as a hobby but rarely played. Comes with soft carry bag and spare set of strings. Tuning pegs work fine, good sound.',
                'category'    => 'music',
                'sub_category'=> 'guitars',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'music',
            ],
            [
                'name'        => 'Levi\'s Denim Jacket Size M',
                'description' => 'Levi\'s classic trucker denim jacket, medium wash, size M. Worn a handful of times, no fading or damage. Buttons all intact. Great layering piece for spring and autumn.',
                'category'    => 'fashion',
                'sub_category'=> 'mens-clothing',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'fashion',
            ],
            [
                'name'        => 'Yoga Mat + Resistance Bands Set',
                'description' => 'Non-slip 6mm yoga mat in purple with carry strap, plus a set of 5 resistance bands (different resistance levels). Used at home for about 3 months. Clean and in good condition.',
                'category'    => 'sports',
                'sub_category'=> 'fitness',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'sports',
            ],
            [
                'name'        => 'Wired Gaming Mouse Logitech G102',
                'description' => 'Logitech G102 LIGHTSYNC wired gaming mouse, black. 8000 DPI, RGB lighting. Used for 1 year, sensor works flawlessly. Cable intact with no kinks. Great budget gaming mouse.',
                'category'    => 'gaming',
                'sub_category'=> 'accessories',
                'condition'   => 'Good',
                'location'    => 'Rustavi, Georgia',
                'looking_for' => 'gaming',
            ],
            [
                'name'        => 'Kettle Electric Russell Hobbs',
                'description' => 'Russell Hobbs 1.7L electric kettle in black. Fast boil, auto shut-off. Used daily for 1.5 years, no limescale buildup. Works perfectly. Cord is tidy, no damage.',
                'category'    => 'home-garden',
                'sub_category'=> 'kitchen',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'home-garden',
            ],
            [
                'name'        => 'Converse Chuck Taylor All Star Size 41',
                'description' => 'Converse Chuck Taylor All Star high tops in black canvas, size EU 41. Worn maybe 10 times. Sole is clean, canvas has no tears. Comes with original box.',
                'category'    => 'fashion',
                'sub_category'=> 'shoes',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'fashion',
            ],
            [
                'name'        => 'Backpack Osprey Daylite 13L',
                'description' => 'Osprey Daylite 13L daypack in black. Lightweight and well-organized. Used for city commuting for one semester. No tears or broken zips. Laptop sleeve fits up to 13".',
                'category'    => 'fashion',
                'sub_category'=> 'bags',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'fashion',
            ],
            [
                'name'        => 'PS4 Controller DualShock 4 (Black)',
                'description' => 'Sony DualShock 4 controller for PS4, jet black. Thumbsticks have minor wear but work correctly. Touchpad, triggers and all buttons function normally. Comes with USB charging cable.',
                'category'    => 'gaming',
                'sub_category'=> 'accessories',
                'condition'   => 'Fair',
                'location'    => 'Gori, Georgia',
                'looking_for' => 'gaming',
            ],
            [
                'name'        => 'Potted Snake Plant (Sansevieria)',
                'description' => 'Healthy snake plant (Sansevieria trifasciata), about 50cm tall, in a terracotta pot. Very low maintenance, great for beginners. Self-pickup only in Tbilisi Vake area.',
                'category'    => 'home-garden',
                'sub_category'=> 'garden',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'home-garden',
            ],
            [
                'name'        => 'Xiaomi Mi Band 6',
                'description' => 'Xiaomi Mi Band 6 fitness tracker, black strap. Tracks heart rate, sleep, steps. Screen has no scratches. Battery charges fast and lasts about 2 weeks. Comes with charger.',
                'category'    => 'electronics',
                'sub_category'=> 'wearables',
                'condition'   => 'Good',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Drawing Tablet Wacom Intuos S',
                'description' => 'Wacom Intuos Small (CTL-4100) drawing tablet in black. Works on Windows and Mac. Used for digital art and photo editing. Pen is included. USB cable included. Driver available on Wacom site.',
                'category'    => 'art',
                'sub_category'=> 'digital-art',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Winter Boots Size 39 (Women)',
                'description' => 'Warm waterproof winter boots, black, size EU 39. Worn for one winter. Soles still have good grip. Inner lining is clean and intact. Zip works fine.',
                'category'    => 'fashion',
                'sub_category'=> 'shoes',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'fashion',
            ],
            [
                'name'        => 'Board Game — Catan (English)',
                'description' => 'Catan base game, English edition. All pieces present and in great condition. Played about 15 times. Cards are unmarked. Box has minor shelf wear. Great for family game nights.',
                'category'    => 'toys',
                'sub_category'=> 'board-games',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'toys',
            ],
            [
                'name'        => 'Portable Power Bank 10000mAh',
                'description' => 'Romoss 10000mAh portable power bank, black. Dual USB outputs, micro-USB charging input. Can charge a phone 2-3 times. Used for travel. Works well, original pouch included.',
                'category'    => 'electronics',
                'sub_category'=> 'phones',
                'condition'   => 'Good',
                'location'    => 'Kutaisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Running Shoes Nike Downshifter 11 EU 44',
                'description' => 'Nike Downshifter 11 running shoes in black/white, size EU 44. Used for about 3 months of casual jogging. Cushioning still good, no holes or tears. Clean and odour-free.',
                'category'    => 'sports',
                'sub_category'=> 'fitness',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'sports',
            ],
            [
                'name'        => 'Desk Organiser + Stationery Set',
                'description' => 'Wooden desk organiser with 5 compartments plus a set of pens, highlighters and sticky notes. Used on a home desk for one year. Clean and practical. All pens work.',
                'category'    => 'office',
                'sub_category'=> 'stationery',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'office',
            ],
            [
                'name'        => 'Baby Carrier Ergobaby Omni 360',
                'description' => 'Ergobaby Omni 360 baby carrier in grey. Used for about 6 months, washed and clean. All buckles and straps work correctly. Suitable from newborn to toddler (up to 20kg).',
                'category'    => 'baby',
                'sub_category'=> 'baby-clothes',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'baby',
            ],
            [
                'name'        => 'Electric Shaver Philips Series 3000',
                'description' => 'Philips Series 3000 dry electric shaver. Used for 8 months, blades still sharp. Comes with cleaning brush and charging cable. Works well on both short and longer stubble.',
                'category'    => 'beauty',
                'sub_category'=> 'grooming',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
            ],
            [
                'name'        => 'Hammock with Hanging Kit',
                'description' => 'Cotton woven hammock in blue and white stripes, 200x150cm. Comes with hanging straps, carabiners and a carry pouch. Used in the garden for two summers. Still colourful and strong.',
                'category'    => 'home-garden',
                'sub_category'=> 'garden',
                'condition'   => 'Good',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'home-garden',
            ],
            [
                'name'        => 'Cat Bed + Toy Bundle',
                'description' => 'Round fluffy cat bed (50cm diameter) in grey, plus a set of 6 cat toys (feather wands, balls, mouse). Bed is clean and washed. My cat preferred sleeping elsewhere. Toys barely used.',
                'category'    => 'pets',
                'sub_category'=> 'pet-supplies',
                'condition'   => 'Like New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'pets',
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
