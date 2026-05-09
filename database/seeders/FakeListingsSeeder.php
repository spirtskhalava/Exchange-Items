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

        // ── 3. Real photos from mymarket.ge (downloaded & converted to WebP locally) ──
        // Photos taken by real Georgian users with phones — stored in storage/app/public/images/seed/
        $p = fn(string $name) => "images/seed/{$name}.webp";

        // ── 4. Listings — everyday clutter people want to swap ───
        $listings = [
            [
                'name'        => '6 Mixed USB & HDMI Cables',
                'description' => 'Bunch of cables I never use: 2x USB-A to micro-USB, 1x USB-C, 1x HDMI 1.5m, 2x old Nokia chargers. All tested and working. Just taking up drawer space.',
                'category'    => 'electronics',
                'sub_category'=> 'accessories',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'books',
                'photos'      => [$p('seed_cable'), $p('seed_cable2')],
            ],
            [
                'name'        => 'Stack of 12 Paperback Novels',
                'description' => 'Mixed genre paperbacks — thrillers, romance, sci-fi. Authors include Dan Brown, Nora Roberts, Isaac Asimov. Read once, spines intact. Happy to split into smaller bundles.',
                'category'    => 'books',
                'sub_category'=> 'fiction',
                'condition'   => 'Good',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'home-garden',
                'photos'      => [$p('seed_book'), $p('seed_copybook')],
            ],
            [
                'name'        => '3 Country Flags (EU, Italy, Brazil)',
                'description' => 'Three 90x150cm polyester flags — EU, Italy, Brazil. Bought for a World Cup party, never used again. Still in original packaging.',
                'category'    => 'home-garden',
                'sub_category'=> 'decoration',
                'condition'   => 'Like New',
                'location'    => 'Kutaisi, Georgia',
                'looking_for' => 'sports',
                'photos'      => [$p('seed_hat'), $p('seed_magnet')],
            ],
            [
                'name'        => 'Box of 8 Unused A5 Notebooks',
                'description' => 'Eight A5 lined notebooks, all blank inside. Mixed brands — Leuchtturm, Rhodia, and some generic. Got them as gifts over the years. Perfect for school or journaling.',
                'category'    => 'office',
                'sub_category'=> 'stationery',
                'condition'   => 'New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'books',
                'photos'      => [$p('seed_copybook'), $p('seed_book')],
            ],
            [
                'name'        => 'School Sports Medals (5 pcs)',
                'description' => 'Five participation/runner-up medals from school athletics events (2018–2022). Gold, silver, bronze plated. Sentimental to someone maybe, I\'ve moved on. Ribbon included on each.',
                'category'    => 'sports',
                'sub_category'=> 'fitness',
                'condition'   => 'Good',
                'location'    => 'Rustavi, Georgia',
                'looking_for' => 'toys',
                'photos'      => [$p('seed_magnet'), $p('seed_cup')],
            ],
            [
                'name'        => '4 Rolls of Unused Wrapping Paper',
                'description' => 'Four full rolls of gift wrapping paper — two Christmas patterns, one birthday stripes, one plain kraft. Stored flat, no creases. Also includes a bag of ribbon and bows.',
                'category'    => 'home-garden',
                'sub_category'=> 'decoration',
                'condition'   => 'New',
                'location'    => 'Gori, Georgia',
                'looking_for' => 'office',
                'photos'      => [$p('seed_cup'), $p('seed_hat')],
            ],
            [
                'name'        => 'Folder of Old Postcards & Stamps',
                'description' => 'Collection of ~80 postcards from various countries plus a small album of used postage stamps (mix of European + US). Some cards dated 1990s. Interesting for collectors.',
                'category'    => 'art',
                'sub_category'=> 'collectibles',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'books',
                'photos'      => [$p('seed_book'), $p('seed_magnet')],
            ],
            [
                'name'        => 'Acrylic Paint Set (18 tubes, unused)',
                'description' => 'Set of 18 acrylic paint tubes, 75ml each. Bought for a project that never happened. All caps sealed, no dried paint. Standard colours including white, black, primary set.',
                'category'    => 'art',
                'sub_category'=> 'supplies',
                'condition'   => 'New',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'office',
                'photos'      => [$p('seed_glasses'), $p('seed_cup')],
            ],
            [
                'name'        => 'Old USB Optical Mouse',
                'description'  => 'Wired USB optical mouse. Works fine, scroll wheel works, minor scuff on top. Good spare or for someone with a budget setup. Tried and tested — no double-click issues.',
                'category'    => 'electronics',
                'sub_category'=> 'accessories',
                'condition'   => 'Fair',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'electronics',
                'photos'      => [$p('seed_mouse'), $p('seed_cable')],
            ],
            [
                'name'        => 'Bag of Lego Mixed Bricks (~500 pcs)',
                'description' => 'Large zip-lock bag of mixed Lego bricks, ~500 pieces. Various colours and sizes, no set included. Clean and sorted by colour. Great for free building or topping up a collection.',
                'category'    => 'toys',
                'sub_category'=> 'building-toys',
                'condition'   => 'Good',
                'location'    => 'Kutaisi, Georgia',
                'looking_for' => 'toys',
                'photos'      => [$p('seed_cup'), $p('seed_copybook')],
            ],
            [
                'name'        => '2 Picture Frames (A4 + A3, Black)',
                'description' => 'Two black wooden picture frames — one A4 and one A3. Glass intact, backs intact. Bought, put on wall for a month, then redecorated. Clean and sturdy.',
                'category'    => 'home-garden',
                'sub_category'=> 'decoration',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'home-garden',
                'photos'      => [$p('seed_hat'), $p('seed_glasses')],
            ],
            [
                'name'        => 'Travel Adapter Set (5 plugs)',
                'description' => 'Universal travel adapter set with 5 plug types: UK, EU, US, AU, CH. All in a small pouch. Used on one trip, all work. Redundant since I got a universal adapter.',
                'category'    => 'electronics',
                'sub_category'=> 'accessories',
                'condition'   => 'Like New',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'electronics',
                'photos'      => [$p('seed_cable2'), $p('seed_mouse')],
            ],
            [
                'name'        => 'Hardcover Atlas (World, 2015 Edition)',
                'description' => 'Large hardcover world atlas, 2015 edition. Full colour maps, 320 pages. Spine intact, no writing inside. Heavy — about 2kg. Decorative or educational.',
                'category'    => 'books',
                'sub_category'=> 'non-fiction',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'books',
                'photos'      => [$p('seed_book'), $p('seed_glasses')],
            ],
            [
                'name'        => '3 Scented Candles (Unopened)',
                'description' => 'Three boxed scented candles — lavender, vanilla, and cedar. Received as gifts, not my thing. All sealed in original boxes. Burn time ~40h each.',
                'category'    => 'home-garden',
                'sub_category'=> 'decoration',
                'condition'   => 'New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'home-garden',
                'photos'      => [$p('seed_magnet'), $p('seed_hat')],
            ],
            [
                'name'        => 'Plastic Storage Box Set (5 boxes)',
                'description' => 'Five stackable plastic storage boxes with lids. Sizes: 2x small (shoebox), 2x medium, 1x large. Clear sides so you can see contents. Good for garage or wardrobe.',
                'category'    => 'home-garden',
                'sub_category'=> 'storage',
                'condition'   => 'Good',
                'location'    => 'Rustavi, Georgia',
                'looking_for' => 'home-garden',
                'photos'      => [$p('seed_cup'), $p('seed_cable')],
            ],
            [
                'name'        => 'Old School Textbooks (Maths + Physics)',
                'description' => 'Two secondary school textbooks: Maths Grade 11 and Physics Grade 10 (English edition). Some pencil notes inside, no missing pages. Useful for revision or tutoring.',
                'category'    => 'books',
                'sub_category'=> 'educational',
                'condition'   => 'Fair',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'books',
                'photos'      => [$p('seed_copybook'), $p('seed_book')],
            ],
            [
                'name'        => 'Desk Globe (28cm diameter)',
                'description' => 'Classic desk globe, 28cm diameter, on a black plastic stand. Political map with country names. Minor scuff on South America, otherwise good condition. Nice desk piece.',
                'category'    => 'office',
                'sub_category'=> 'decoration',
                'condition'   => 'Good',
                'location'    => 'Gori, Georgia',
                'looking_for' => 'office',
                'photos'      => [$p('seed_glasses'), $p('seed_magnet')],
            ],
            [
                'name'        => 'Bundle of Extension Cords (3 pcs)',
                'description' => 'Three extension cords: 1x 3m with 4 sockets, 1x 5m single socket, 1x 2m with USB ports. All EU plugs, all work. Replaced with smart plugs.',
                'category'    => 'home-garden',
                'sub_category'=> 'tools',
                'condition'   => 'Good',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'electronics',
                'photos'      => [$p('seed_cable'), $p('seed_cable2')],
            ],
            [
                'name'        => 'Trophy Cup — 1st Place (Generic)',
                'description' => 'Generic gold-coloured plastic trophy cup, 30cm tall. Says "1st Place" on the base. Won at a local quiz night. Would suit a kid\'s room, gag gift, or ironic shelf decor.',
                'category'    => 'sports',
                'sub_category'=> 'collectibles',
                'condition'   => 'Good',
                'location'    => 'Kutaisi, Georgia',
                'looking_for' => 'toys',
                'photos'      => [$p('seed_hat'), $p('seed_cup')],
            ],
            [
                'name'        => 'Jigsaw Puzzle 1000 pcs — Eiffel Tower',
                'description' => '1000-piece jigsaw puzzle, Eiffel Tower at night. Completed once, all pieces present and accounted for, stored in zip bags. Box has corner dents but puzzle is perfect.',
                'category'    => 'toys',
                'sub_category'=> 'puzzles',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'toys',
                'photos'      => [$p('seed_book'), $p('seed_copybook')],
            ],
            [
                'name'        => 'Bunch of Pens & Markers (30+ pcs)',
                'description' => 'Assorted pens, biros, and markers — all tested and writing. Includes a set of 12 Stabilo markers, several ballpoints, and 4 highlighters. Cleared out a desk drawer.',
                'category'    => 'office',
                'sub_category'=> 'stationery',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'office',
                'photos'      => [$p('seed_mouse'), $p('seed_glasses')],
            ],
            [
                'name'        => 'Vintage Wall Clock (Wooden)',
                'description' => 'Round wooden wall clock, 32cm, battery-powered (AA). Roman numerals. Works perfectly. Replaced with a minimalist one. Suits a rustic or classic interior.',
                'category'    => 'home-garden',
                'sub_category'=> 'decoration',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'home-garden',
                'photos'      => [$p('seed_magnet'), $p('seed_cup')],
            ],
            [
                'name'        => 'Box of Christmas Decorations',
                'description' => 'Full box of Christmas ornaments: glass baubles (mixed colours), tinsel, 2 strings of LED lights (working), and a star tree topper. Been in storage 3 years.',
                'category'    => 'home-garden',
                'sub_category'=> 'decoration',
                'condition'   => 'Good',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'home-garden',
                'photos'      => [$p('seed_glasses'), $p('seed_hat')],
            ],
            [
                'name'        => 'Magnetic Whiteboard (60x40cm)',
                'description' => 'Small magnetic whiteboard, 60x40cm, with aluminium frame. Comes with 2 markers and an eraser. Minor ghost marks from old writing but cleans fully with proper cleaner.',
                'category'    => 'office',
                'sub_category'=> 'stationery',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'office',
                'photos'      => [$p('seed_copybook'), $p('seed_mouse')],
            ],
            [
                'name'        => 'Spare Bike Accessories Bundle',
                'description' => 'Bike stuff I upgraded away from: 1 bell, 1 rear reflector, 1 water bottle cage, 1 saddle bag (small), 1 basic bike lock (cable). All in working condition.',
                'category'    => 'sports',
                'sub_category'=> 'outdoor-sports',
                'condition'   => 'Good',
                'location'    => 'Kutaisi, Georgia',
                'looking_for' => 'sports',
                'photos'      => [$p('seed_cable'), $p('seed_mouse')],
            ],
        ];

        // ── 5. Insert products ────────────────────────────────────
        foreach ($listings as $i => $data) {
            $user   = $users[$i % count($users)];
            $coords = \App\Http\Controllers\MapController::geocode($data['location']);

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
                'latitude'     => $coords['lat'] ?? null,
                'longitude'    => $coords['lng'] ?? null,
            ]);

            // Nominatim rate limit: 1 req/sec
            usleep(1100000);
        }

        $this->command->info('✓ Created ' . count($users) . ' users and ' . count($listings) . ' listings.');
    }
}
