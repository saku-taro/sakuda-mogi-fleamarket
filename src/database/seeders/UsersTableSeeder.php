<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'postcode' => '123-4567',
            'address' => '東京都テスト区テスト町1-2-3',
            'building' => 'テストビル 5F',
            'is_profile_completed' => true,
        ]);
    }
}
