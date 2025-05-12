<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()
            ->reservations()
            ->with(['service', 'status', 'user'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('service', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('status'), fn($q) => $q->where('status_id', $request->status));

        if ($request->filled('sort_by') && $request->filled('order')) {
            if ($request->sort_by === 'service_name') {
                $query->join('services', 'reservations.service_id', '=', 'services.id')
                      ->orderBy('services.name', $request->order)
                      ->select('reservations.*');
            } else {
                $query->orderBy($request->sort_by, $request->order);
            }
        } else {
            $query->latest();
        }

        return response()->json($query->paginate(10)->appends($request->query()));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'reservation_time' => 'required|date|after:now',
        ]);

        $exists = Reservation::where('service_id', $validated['service_id'])
            ->where('reservation_time', $validated['reservation_time'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'This time slot is already reserved.'], 422);
        }

        $reservation = $request->user()->reservations()->create([
            'service_id' => $validated['service_id'],
            'reservation_time' => $validated['reservation_time'],
            'status_id' => ReservationStatus::PENDING,
        ]);

        return response()->json($reservation->load('service', 'status'), 201);
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        return response()->json($reservation->load('service', 'status'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'status_id' => 'required|exists:reservation_statuses,id',
        ]);

        $reservation->update($validated);

        return response()->json($reservation->load('service', 'status'));
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        if ($reservation->reservation_time < now()) {
            return response()->json(['message' => 'You cannot cancel a past reservation.'], 400);
        }

        if ($reservation->status_id == ReservationStatus::CANCELLED) {
            return response()->json(['message' => 'Already cancelled.'], 400);
        }

        if ($reservation->status_id == ReservationStatus::CONFIRMED) {
            return response()->json(['message' => 'Cannot cancel confirmed reservation.'], 400);
        }

        $reservation->update(['status_id' => ReservationStatus::CANCELLED]);

        return response()->json(['message' => 'Reservation cancelled.']);
    }
}
