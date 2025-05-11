<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Reservation::with('service', 'user', 'status')->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'reservation_time' => 'required|date|after:now',
        ]);

        $reservation = $request->user()->reservations()->create($validated);

        return response()->json($reservation, 201);
    }

    public function show(Reservation $reservation)
    {
        return response()->json($reservation->load('service', 'status'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'status_id' => 'exists:reservation_statuses,id',
        ]);

        $reservation->update($validated);

        return response()->json($reservation);
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        $reservation->delete();

        return response()->json(null, 204);
    }
}
