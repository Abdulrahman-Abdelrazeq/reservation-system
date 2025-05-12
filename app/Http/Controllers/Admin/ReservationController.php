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
         $query = \App\Models\Reservation::with(['service', 'status', 'user']);

        // البحث باسم الخدمة
        if ($request->filled('search')) {
            $query->whereHas('service', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // البحث باسم المستخدم
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        // فلترة بالحالة
        if ($request->filled('status')) {
            $query->where('status_id', $request->status);
        }

        // ترتيب حسب حقل
        if ($request->filled('sort_by') && $request->filled('order')) {
            if ($request->sort_by === 'service_name') {
                $query->join('services', 'reservations.service_id', '=', 'services.id')
                    ->orderBy('services.name', $request->order)
                    ->select('reservations.*');
            } elseif ($request->sort_by === 'user_name') {
                $query->join('users', 'reservations.user_id', '=', 'users.id')
                    ->orderBy('users.name', $request->order)
                    ->select('reservations.*');
            } else {
                $query->orderBy($request->sort_by, $request->order);
            }
        } else {
            $query->latest();
        }
        
        $reservations = $query->paginate(config('app.per_page'))->appends($request->query());

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
