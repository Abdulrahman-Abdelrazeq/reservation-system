<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Reservation;
use App\Models\ReservationStatus;

class ReservationController extends Controller
{
    use Response;

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

        return $this->sendRes(true, 'Services retrieved successfully', $query->paginate(config('app.per_page'))->appends($request->query()));
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
            return $this->sendRes(false, 'This time slot is already reserved.', null, null, 400);
        }

        $reservation = $request->user()->reservations()->create([
            'service_id' => $validated['service_id'],
            'reservation_time' => $validated['reservation_time'],
            'status_id' => ReservationStatus::PENDING,
        ]);

        return $this->sendRes(true, 'Reservation created successfully', $reservation->load('service', 'status'));
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        return $this->sendRes(true, 'Reservation retrieved successfully', $reservation->load('service', 'status'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'status_id' => 'required|exists:reservation_statuses,id',
        ]);

        $reservation->update($validated);

        return $this->sendRes(true, 'Reservation status updated successfully', $reservation->load('service', 'status'));
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        if ($reservation->reservation_time < now()) {
            return $this->sendRes(false, 'Cannot cancel past reservation.', null, null, 400);
        }

        if ($reservation->status_id == ReservationStatus::CANCELLED) {
            return $this->sendRes(false, 'This reservation is already cancelled.', null, null, 400);
        }

        if ($reservation->status_id == ReservationStatus::CONFIRMED) {
            return $this->sendRes(false, 'Cannot cancel a confirmed reservation.', null, null, 400);
        }

        $reservation->update(['status_id' => ReservationStatus::CANCELLED]);

        return $this->sendRes(true, 'Reservation cancelled.');
    }
}
