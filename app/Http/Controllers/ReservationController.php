<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\ReservationStatus;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Auth::user()->reservations()->with('service', 'status')->latest()->paginate(10);
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::where('available', true)->get();

        return view('reservations.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request, Service $service)
    {
        $reservationExists = Reservation::where('service_id', $service->id)
            ->where('reservation_time', $request->reservation_time)
            ->exists();

        if ($reservationExists) {
            return back()->withErrors(['reservation_time' => 'This time slot is already reserved.']);
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'reservation_time' => $request->reservation_time,
            'status_id' => ReservationStatus::PENDING,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully!');
    }

    // Cancel a reservation
    public function cancel(Reservation $reservation)
    {
       if ($reservation->user_id !== Auth::id()) {
           abort(403);
       }

       // Optional: prevent canceling past reservations
       if ($reservation->reservation_time < now()) {
           return back()->withErrors(['error' => 'You cannot cancel a past reservation.']);
       }

       $reservation->status_id = ReservationStatus::CANCELLED;
       $reservation->save();

       return back()->with('success', 'Reservation cancelled.');
    }
}
