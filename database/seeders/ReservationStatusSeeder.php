<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReservationStatus;

class ReservationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReservationStatus::insert([
            [
                'name' => 'pending',
                'color' => 'yellow',
                'icon' => 'fa-clock',
            ],
            [
                'name' => 'confirmed',
                'color' => 'green',
                'icon' => 'fa-check-circle',
            ],
            [
                'name' => 'cancelled',
                'color' => 'red',
                'icon' => 'fa-times-circle',
            ],
        ]);
    }
}
