
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Service List') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container">
            <h1>Available Services</h1>

            <form method="GET" action="{{ route('services.index') }}" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" placeholder="Search by name or description" value="{{ request('keyword') }}">
                </div>
        
                <div class="col-md-2">
                    <select name="sort_by" class="form-select">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                    </select>
                </div>
        
                <div class="col-md-2">
                    <select name="sort_order" class="form-select">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
        
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('services.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>

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
