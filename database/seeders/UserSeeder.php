<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'テストユーザー',
                'email' => 'user@gmail.com',
                'password' => 'password',
                'is_admin' => false,
            ],
            [
                'name' => 'テスト管理者',
                'email' => 'admin@gmail.com',
                'password' => 'password',
                'is_admin' => true,
            ],
        ]);
    }
}
