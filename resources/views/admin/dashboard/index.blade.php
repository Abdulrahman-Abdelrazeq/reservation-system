
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-dark leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="container">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text h3">{{ $usersCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Services</h5>
                    <p class="card-text h3">{{ $servicesCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Reservations</h5>
                    <p class="card-text h3">{{ $reservationsCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-4">Reservations by Status</h3>
    <canvas id="reservationChart" height="100"></canvas>
</div>
    </div>
    <script>
    const ctx = document.getElementById('reservationChart').getContext('2d');
    const reservationChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($reservationStatusCounts->toArray())) !!},
            datasets: [{
                label: 'Reservations Count',
                data: {!! json_encode(array_values($reservationStatusCounts->toArray())) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>
</x-app-layout>
