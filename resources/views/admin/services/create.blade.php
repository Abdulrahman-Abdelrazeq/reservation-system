
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Add New Service') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container">
            <h1 class="mb-4">Create Service</h1>

            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Service Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Service Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Service Price</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" step="0.01" required>
                    @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="available" id="available" value="1" {{ old('available', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="available">Available</label>
                </div>

                <button type="submit" class="btn btn-success">Create</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</x-app-layout>
