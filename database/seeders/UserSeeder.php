<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'john_doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'jane_doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'alice',
            'email' => 'alice@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'bob',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
