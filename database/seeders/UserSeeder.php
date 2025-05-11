<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Abdo',
            'email' => 'abdo@gmail.com',
            'password' => bcrypt('abdo2025'),
            'role_id' => Role::ADMIN,
        ]);

        User::factory(10)->create();
    }
}
