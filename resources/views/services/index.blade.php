
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Service List') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container">
            <h1>Available Services</h1>
            <div class="row">
                @foreach($services as $service)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $service->name }}</h5>
                                <p>{{ Str::limit($service->description, 100) }}</p>
                                <p><strong>${{ number_format($service->price, 2) }}</strong></p>
                                <a href="{{ route('services.show', $service) }}" class="btn btn-primary">View & Reserve</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $services->links() }}
        </div>
    </div>
</x-app-layout>
