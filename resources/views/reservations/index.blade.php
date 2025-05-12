
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

            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by Service Name..." value="{{ request('search') }}">
                </div>

                @if(auth()->user()->isAdmin())
                    <div class="col-md-3">
                        <input type="text" name="user" class="form-control" placeholder="Search by User Name..." value="{{ request('user') }}">
                    </div>
                @endif

                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Pending</option>
                        <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Confirmed</option>
                        <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="sort_by" class="form-select">
                        <option value="">Sort by</option>
                        <option value="reservation_time" {{ request('sort_by') == 'reservation_time' ? 'selected' : '' }}>Reservation Time</option>
                        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                        <option value="service_name" {{ request('sort_by') == 'service_name' ? 'selected' : '' }}>Service Name</option>
                        @if(auth()->user()->isAdmin())
                            <option value="user_name" {{ request('sort_by') == 'user_name' ? 'selected' : '' }}>User Name</option>
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="order" class="form-select">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-primary w-100">Apply</button>
                </div>

                <div class="col-md-1">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.reservations.index') : route('reservations.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>


        
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service</th>
                        @if(auth()->user()->isAdmin())
                            <th>User</th>
                        @endif
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->service->name }}</td>
                            @if(auth()->user()->isAdmin())
                                <td>{{ $reservation->user->name }}</td>
                            @endif
                            <td>{{ $reservation->reservation_time->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $reservation->status_id ==  \App\Models\ReservationStatus::PENDING ? 'warning' : ($reservation->status_id == \App\Models\ReservationStatus::CONFIRMED ? 'success' : 'danger') }}">
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
                                @elseif($reservation->reservation_time > now() && $reservation->status_id == \App\Models\ReservationStatus::PENDING)
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
