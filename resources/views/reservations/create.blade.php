
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Add New Reservation') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container">
            <h1 class="mb-4">New Reservation</h1>
        
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
        
                <div class="mb-3">
                    <label for="service_id" class="form-label">Service</label>
                    <select name="service_id" class="form-select" required>
                        <option value="">Select Service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} (${{ $service->price }})
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <div class="mb-3">
                    <label for="reservation_time" class="form-label">Date & Time</label>
                    <input type="datetime-local" name="reservation_time" class="form-control" value="{{ old('reservation_time') }}" required>
                </div>
        
                <button type="submit" class="btn btn-primary">Reserve</button>
                <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</x-app-layout>
