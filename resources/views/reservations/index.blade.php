
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Reservations List') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container">
            <h1 class="mb-4">My Reservations</h1>
        
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->service->name }}</td>
                            <td>{{ $reservation->reservation_time->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $reservation->status_id == \App\Models\ReservationStatus::CANCELLED ? 'warning' : 'success' }}">
                                    {{ ucfirst($reservation->status->name) }}
                                </span>
                            </td>
                            <td>
                                @if(Auth::user()->role->name === 'admin')
                                    <form action="{{ route('admin.reservations.updateStatus', $reservation) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status_id" onchange="this.form.submit()" class="form-select form-select-sm">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" {{ $reservation->status_id == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                @elseif($reservation->reservation_time > now() && $reservation->status_id != \App\Models\ReservationStatus::CANCELLED)
                                    <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('Are you sure to cancel?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No reservations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        
            <div>{{ $reservations->links() }}</div>
        </div>
    </div>
</x-app-layout>
