<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $products = [
            [
                'user_id' => 1,
                'name' => 'Laptop',
                'description' => 'A powerful laptop with 16GB RAM and 512GB SSD.',
            ],
            [
                'user_id' => 1,
                'name' => 'Smartphone',
                'description' => 'A modern smartphone with a great camera.',
            ],
            [
                'user_id' => 2,
                'name' => 'Tablet',
                'description' => 'A high-resolution tablet perfect for media consumption.',
            ],
            [
                'user_id' => 2,
                'name' => 'Headphones',
                'description' => 'Noise-cancelling over-ear headphones.',
            ],
            [
                'user_id' => 3,
                'name' => 'Smartwatch',
                'description' => 'A smartwatch with fitness tracking capabilities.',
            ],
            [
                'user_id' => 4,
                'name' => 'Gaming Console',
                'description' => 'A latest-generation gaming console.',
            ],
        ];

        foreach ($products as $product) {
            // Generate an array of image URLs
            $imagePaths = [
                $faker->imageUrl(200, 200, 'electronics', true, 'product-1'),
                $faker->imageUrl(200, 200, 'electronics', true, 'product-2'),
                $faker->imageUrl(200, 200, 'electronics', true, 'product-3'),
            ];

            // Save the product with the image paths as JSON
            Product::create([
                'user_id' => $product['user_id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'category' => 'Electronics',
                'condition'=>'New',
                'location'=>'Tbilisi, Georgia',
                'hide'=>0,
                'image_paths' => json_encode($imagePaths),  // Save as JSON
            ]);
        }
    }
}