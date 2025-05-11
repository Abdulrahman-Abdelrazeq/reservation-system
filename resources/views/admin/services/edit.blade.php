
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Edit Service') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container">
            <h1 class="mb-4">Edit Service</h1>

            <form action="{{ route('services.update', $service) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Service Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $service->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Service Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description', $service->description) }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Service Price</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $service->price) }}" step="0.01" required>
                    @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="available" id="available" value="1" {{ old('available', $service->available) ? 'checked' : '' }}>
                    <label class="form-check-label" for="available">Available</label>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('services.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</x-app-layout>

