<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\ReservationStatus;
use App\Models\User;
use App\Models\Role;
use App\Models\Service;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ReservationStatus::pluck('id')->toArray();
        $users = User::where('role_id', '!=', Role::ADMIN)->pluck('id')->toArray();
        $services = Service::pluck('id')->toArray();

        if (empty($statuses) || empty($users) || empty($services)) {
            $this->command->warn('Missing required data: statuses, users or services.');
            return;
        }

        

        foreach (range(1, 20) as $i) {
            Reservation::create([
                'user_id' => $users[array_rand($users)],
                'service_id' => $services[array_rand($services)],
                'reservation_time' => Carbon::now()->addDays(rand(1, 30)),
                'status_id' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
