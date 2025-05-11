<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ReservationStatus;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = Reservation::with('user', 'service')
            ->orderBy('reservation_time', 'desc')
            ->paginate(10);

        $statuses = ReservationStatus::all();

        return view('reservations.index', compact(['reservations', 'statuses']));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status_id' => 'required|exists:reservation_statuses,id',
        ]);

        $reservation->update(['status_id' => $request->status_id]);

        return redirect()->back()->with('success', 'Reservation status updated.');
    }
}
