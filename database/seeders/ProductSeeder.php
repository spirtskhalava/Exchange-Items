<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'user_id' => 1,
            'name' => 'Laptop',
            'description' => 'A powerful laptop with 16GB RAM and 512GB SSD.',
        ]);

        Product::create([
            'user_id' => 1,
            'name' => 'Smartphone',
            'description' => 'A modern smartphone with a great camera.',
        ]);

        Product::create([
            'user_id' => 2,
            'name' => 'Tablet',
            'description' => 'A high-resolution tablet perfect for media consumption.',
        ]);

        Product::create([
            'user_id' => 2,
            'name' => 'Headphones',
            'description' => 'Noise-cancelling over-ear headphones.',
        ]);

        Product::create([
            'user_id' => 3,
            'name' => 'Smartwatch',
            'description' => 'A smartwatch with fitness tracking capabilities.',
        ]);

        Product::create([
            'user_id' => 4,
            'name' => 'Gaming Console',
            'description' => 'A latest-generation gaming console.',
        ]);
    }
}
