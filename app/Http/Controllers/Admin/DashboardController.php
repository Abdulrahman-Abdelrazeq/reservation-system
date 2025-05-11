<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $servicesCount = Service::count();
        $reservationsCount = Reservation::count();

        $reservationStatusCounts = Reservation::with('status')
        ->select('status_id')
        ->selectRaw('COUNT(*) as count')
        ->groupBy('status_id')
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->status->name => $item->count];
        });

        return view('admin.dashboard.index', compact(
            'usersCount',
            'servicesCount',
            'reservationsCount',
            'reservationStatusCounts'
        ));
    }
}
