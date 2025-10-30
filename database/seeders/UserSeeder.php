<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run(): void
    {

        User::factory()->create([
            'name' => 'Quản trị viên',
            'email' => 'admin@payapp.vn',
            'password' => bcrypt('Admin@12345'),
        ]);

        User::factory()->create([
            'name' => 'Nguyễn Văn An',
            'email' => 'an.nguyen@payapp.vn',
            'password' => bcrypt('User@12345'),
        ]);
        User::factory()->create([
            'name' => 'Trần Thu Hà',
            'email' => 'ha.tran@payapp.vn',
            'password' => bcrypt('User@12345'),
        ]);
    }
}
