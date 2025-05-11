
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Make Reservation') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container">
            <h1>{{ $service->name }}</h1>
            <p>{{ $service->description }}</p>
            <p><strong>Price:</strong> ${{ number_format($service->price, 2) }}</p>

            <hr>

            <h4>Reserve this service</h4>
            <form action="{{ route('reservations.store', $service) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Date</label>
                    <input type="datetime-local" name="reservation_time" class="form-control" required>
                </div>
                <button class="btn btn-success">Reserve</button>
            </form>
        </div>
    </div>
</x-app-layout>

