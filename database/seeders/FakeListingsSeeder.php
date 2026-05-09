<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FakeListingsSeeder extends Seeder
{
    // All seeder emails across all versions — ensures clean re-runs
    private array $seederEmails = [
        // current
        'nino.tbilisi@example.com',
        'giorgi.swap@example.com',
        'mari.batumi@example.com',
        'davit.kutaisi@example.com',
        'ana.rustavi@example.com',
        'luka.gori@example.com',
        'tamta.zugdidi@example.com',
        'irakli.telavi@example.com',
        'salome.mtskheta@example.com',
        // legacy (previous seeder versions)
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
            ['name' => 'nino_t',      'email' => 'nino.tbilisi@example.com'],
            ['name' => 'giorgi93',    'email' => 'giorgi.swap@example.com'],
            ['name' => 'mari_b',      'email' => 'mari.batumi@example.com'],
            ['name' => 'davit_k',     'email' => 'davit.kutaisi@example.com'],
            ['name' => 'ana.r',       'email' => 'ana.rustavi@example.com'],
            ['name' => 'luka_gori',   'email' => 'luka.gori@example.com'],
            ['name' => 'tamta88',     'email' => 'tamta.zugdidi@example.com'],
            ['name' => 'irakli_t',    'email' => 'irakli.telavi@example.com'],
            ['name' => 'salome_m',    'email' => 'salome.mtskheta@example.com'],
        ];

        $users = [];
        foreach ($usersData as $u) {
            $users[] = User::create([
                'name'     => $u['name'],
                'email'    => $u['email'],
                'password' => Hash::make('password123'),
            ]);
        }

        // ── 3. Real photos from mymarket.ge — 1 unique photo per listing ──
        $p = fn(string $name) => "images/seed/{$name}.webp";

        // ── 4. 9 listings — each with its own unique photo ───────
        $listings = [
            [
                'name'        => '6 Mixed USB & HDMI Cables',
                'description' => 'Bunch of cables I never use: 2× USB-A to micro-USB, 1× USB-C, 1× HDMI 1.5m, 2× old Nokia chargers. All tested and working. Just taking up drawer space.',
                'category'    => 'electronics',
                'sub_category'=> 'accessories',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'books',
                'photos'      => [$p('seed_cable')],
            ],
            [
                'name'        => 'Apple USB-C Charging Cable (2m)',
                'description' => 'Original Apple USB-C to Lightning cable, 2 metres. Bought with an iPhone, already have two others. Works perfectly, no fraying. Comes in original packaging.',
                'category'    => 'electronics',
                'sub_category'=> 'accessories',
                'condition'   => 'Like New',
                'location'    => 'Batumi, Georgia',
                'looking_for' => 'electronics',
                'photos'      => [$p('seed_cable2')],
            ],
            [
                'name'        => 'Stack of Paperback Books',
                'description' => 'About 10 paperbacks in mixed genres — thrillers, one travel guide, a couple of classics. Read once each. Happy to split. Free to a good home or swap for anything useful.',
                'category'    => 'books',
                'sub_category'=> 'fiction',
                'condition'   => 'Good',
                'location'    => 'Kutaisi, Georgia',
                'looking_for' => 'home-garden',
                'photos'      => [$p('seed_book')],
            ],
            [
                'name'        => 'School Copybooks — Unused Pack',
                'description' => 'Pack of school copybooks, never written in. Bought for my kid but we already had plenty. Standard lined pages, 12 sheets each. Better than letting them gather dust.',
                'category'    => 'office',
                'sub_category'=> 'stationery',
                'condition'   => 'New',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'toys',
                'photos'      => [$p('seed_copybook')],
            ],
            [
                'name'        => 'Fridge Magnet — Pomegranate Souvenir',
                'description' => 'Decorative fridge magnet, pomegranate design, typical Georgian souvenir. Got it as a gift, my fridge is already full of these. Perfectly fine, nothing broken.',
                'category'    => 'home-garden',
                'sub_category'=> 'decoration',
                'condition'   => 'New',
                'location'    => 'Mtskheta, Georgia',
                'looking_for' => 'home-garden',
                'photos'      => [$p('seed_magnet')],
            ],
            [
                'name'        => 'Glass Set — Luminarc (6 pcs)',
                'description' => 'Six Luminarc drinking glasses, good everyday quality. No chips or cracks. Moving to a smaller flat and already have two sets. Selling as a full set only.',
                'category'    => 'home-garden',
                'sub_category'=> 'kitchen',
                'condition'   => 'Good',
                'location'    => 'Rustavi, Georgia',
                'looking_for' => 'home-garden',
                'photos'      => [$p('seed_cup')],
            ],
            [
                'name'        => 'Traditional Georgian Hat (Khevsurian)',
                'description' => 'Handmade Khevsurian-style hat, bought at a craft market a few years ago. Worn maybe twice. Good condition, no damage. Fits medium-large head. Unique decorative piece.',
                'category'    => 'clothing',
                'sub_category'=> 'accessories',
                'condition'   => 'Good',
                'location'    => 'Tbilisi, Georgia',
                'looking_for' => 'clothing',
                'photos'      => [$p('seed_hat')],
            ],
            [
                'name'        => 'Reading Glasses +1.5 (unisex)',
                'description' => 'Simple reading glasses, +1.5 magnification, black frame. Bought two pairs, only need one. Clean lenses, no scratches. Case not included but can add a soft pouch.',
                'category'    => 'clothing',
                'sub_category'=> 'accessories',
                'condition'   => 'Like New',
                'location'    => 'Gori, Georgia',
                'looking_for' => 'books',
                'photos'      => [$p('seed_glasses')],
            ],
            [
                'name'        => 'Wired USB Mouse',
                'description' => 'Basic wired USB optical mouse. Scroll wheel and all buttons work fine. Small scuff on the top, nothing that affects use. Good backup mouse or for a simple workstation.',
                'category'    => 'electronics',
                'sub_category'=> 'accessories',
                'condition'   => 'Fair',
                'location'    => 'Telavi, Georgia',
                'looking_for' => 'electronics',
                'photos'      => [$p('seed_mouse')],
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
