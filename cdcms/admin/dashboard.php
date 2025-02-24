<?php
require_once('../config/config.php');
require_once('../classes/Statistics.php');
require_once('../templates/header.php');

$stats = new Statistics($conn);

// Fetch all statistics
$totalChildren = $stats->getTotalChildren();
$totalBabysitters = $stats->getTotalBabysitters();
$enrollmentStats = $stats->getEnrollmentStats();
$babysitterBookings = $stats->getBabysitterBookings();
?>

<div class="container-fluid">
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Children</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalChildren ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-child fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Babysitters</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalBabysitters ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Enrollment Status Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Enrollment Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Babysitter Bookings Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Babysitter Bookings</h6>
                </div>
                <div class="card-body">
                    <canvas id="babysitterChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Charts Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enrollment Status Chart
    const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(enrollmentCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($enrollmentStats, 'status')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($enrollmentStats, 'count')) ?>,
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Babysitter Bookings Chart
    const babysitterCtx = document.getElementById('babysitterChart').getContext('2d');
    new Chart(babysitterCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($babysitterBookings, 'name')) ?>,
            datasets: [{
                label: 'Number of Bookings',
                data: <?= json_encode(array_column($babysitterBookings, 'booking_count')) ?>,
                backgroundColor: '#4e73df'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>

<?php
require_once('../templates/footer.php');
?> 