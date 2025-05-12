
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Service List') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container">        
            @auth
                @if (Auth::user()->isAdmin())
                    <div class="mb-4">
                        <a href="{{ route('services.create') }}" class="btn btn-success">Add New Service</a>
                    </div>
                @endif
            @endauth

            <!-- Filter & Sort Form -->
            <form method="GET" action="{{ route('admin.services.index') }}" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" placeholder="Search by name or description" value="{{ request('keyword') }}">
                </div>
        
                <div class="col-md-2">
                    <select name="sort_by" class="form-select">
                        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                        <option value="available" {{ request('sort_by') == 'available' ? 'selected' : '' }}>Availability</option>
                    </select>
                </div>
        
                <div class="col-md-2">
                    <select name="sort_order" class="form-select">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
        
                <div class="col-md-2">
                    <select name="available" class="form-select">
                        <option value="">Availability:</option>
                        <option value="1" {{ request('available') === '1' ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ request('available') === '0' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>
        
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>


        
            <!-- Services Table -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ Str::limit($service->description, 50) }}</td>
                            <td>${{ number_format($service->price, 2) }}</td>
                            <td>
                                <span class="badge {{ $service->available ? 'bg-success' : 'bg-danger' }}">
                                    {{ $service->available ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                @endIf
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No services found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        
            <!-- Pagination Links -->
            <div>
                {{ $services->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
