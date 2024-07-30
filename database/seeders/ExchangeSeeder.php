<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exchange;

class ExchangeSeeder extends Seeder
{
    public function run()
    {
        Exchange::create([
            'requester_id' => 2,
            'responder_id' => 1,
            'requested_product_id' => 1,
            'offered_product_id' => 3,
            'status' => 'pending',
        ]);

        Exchange::create([
            'requester_id' => 3,
            'responder_id' => 2,
            'requested_product_id' => 4,
            'offered_product_id' => 5,
            'status' => 'pending',
        ]);

        Exchange::create([
            'requester_id' => 4,
            'responder_id' => 1,
            'requested_product_id' => 2,
            'offered_product_id' => 6,
            'status' => 'pending',
        ]);
    }
}
