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
    public function index(Request $request)
    {
        $query = Auth::user()
        ->reservations()
        ->with(['service', 'status', 'user'])
        ->whereHas('service', function ($q) use ($request) {
            if ($request->filled('search')) {
                $q->where('name', 'like', '%' . $request->search . '%');
            }
        });

        if ($request->filled('status')) {
            $query->where('status_id', $request->status);
        }

        if ($request->filled('sort_by') && $request->filled('order')) {
            if ($request->sort_by == 'service_name') {
                $query->join('services', 'reservations.service_id', '=', 'services.id')
                    ->orderBy('services.name', $request->order)
                    ->select('reservations.*');
            } else {
                $query->orderBy($request->sort_by, $request->order);
            }
        } else {
            $query->latest();
        }

        $reservations = $query->paginate(10)->appends($request->query());

        return view('reservations.index', compact('reservations'));
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

        //prevent canceling past reservations
        if ($reservation->reservation_time < now()) {
           return back()->withErrors(['error' => 'You cannot cancel a past reservation.']);
        }
        if ($reservation->status_id == ReservationStatus::CANCELLED) {
           return back()->withErrors(['error' => 'This reservation is already cancelled.']);
        }
        if ($reservation->status_id == ReservationStatus::CONFIRMED) {
           return back()->withErrors(['error' => 'You cannot cancel a confirmed reservation.']);
        }


       $reservation->status_id = ReservationStatus::CANCELLED;
       $reservation->save();

       return back()->with('success', 'Reservation cancelled.');
    }
}
