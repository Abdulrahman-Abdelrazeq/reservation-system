
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Service List') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        @php
            use App\Models\\App\Models\ReservationStatus::CANCELLED;
        @endphp

        <div class="container">
            <h1 class="mb-4">Admin Reservation Dashboard</h1>
        
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Service</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->id }}</td>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->service->name }}</td>
                            <td>{{ $reservation->reservation_time->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $reservation->status_id ==  \App\Models\ReservationStatus::CANCELLED::PENDING ? 'warning' : ($reservation->status_id == \App\Models\ReservationStatus::CANCELLED::CONFIRMED ? 'success' : 'danger') }}">
                                    {{ ucfirst($reservation->status->name) }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.reservations.updateStatus', $reservation) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                        <option value="{{ \App\Models\ReservationStatus::CANCELLED::PENDING }}" {{ $reservation->status_id == \App\Models\ReservationStatus::CANCELLED::PENDING ? 'selected' : '' }}>Pending</option>
                                        <option value="{{ \App\Models\ReservationStatus::CANCELLED::CONFIRMED }}" {{ $reservation->status_id == \App\Models\ReservationStatus::CANCELLED::CONFIRMED ? 'selected' : '' }}>Confirmed</option>
                                        <option value="{{ \App\Models\ReservationStatus::CANCELLED::CANCELLED }}" {{ $reservation->status_id == \App\Models\ReservationStatus::CANCELLED::CANCELLED ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No reservations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        
            <div>{{ $reservations->links() }}</div>
        </div>
    </div>
</x-app-layout>
