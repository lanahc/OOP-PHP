<?php
// This will be included in your home.php or index.php in admin
try {
    $stats = $conn->query("SELECT 
        (SELECT COUNT(*) FROM children WHERE delete_flag = 0) as total_children,
        (SELECT COUNT(*) FROM babysitters WHERE delete_flag = 0) as total_babysitters,
        (SELECT COUNT(*) FROM enrollments WHERE status = 'pending' AND delete_flag = 0) as pending_enrollments,
        (SELECT COUNT(*) FROM enrollments WHERE status = 'confirmed' AND delete_flag = 0) as confirmed_enrollments
    ");
    
    if ($stats) {
        $stats_row = $stats->fetch(PDO::FETCH_ASSOC);
    } else {
        $stats_row = [
            'total_children' => 0,
            'total_babysitters' => 0,
            'pending_enrollments' => 0,
            'confirmed_enrollments' => 0
        ];
    }
} catch (PDOException $e) {
    // Handle any database errors
    $stats_row = [
        'total_children' => 0,
        'total_babysitters' => 0,
        'pending_enrollments' => 0,
        'confirmed_enrollments' => 0
    ];
    error_log("Database Error: " . $e->getMessage());
}
?>

<div class="row">
    <!-- Service List -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= number_format($stats_row['total_services']) ?></h3>
                <p>Service List</p>
            </div>
            <div class="icon">
                <i class="fas fa-list"></i>
            </div>
        </div>
    </div>

    <!-- Babysitters List -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3><?= number_format($stats_row['total_babysitters']) ?></h3>
                <p>Babysitters List</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>
    </div>

    <!-- Pending Enrollee -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= number_format($stats_row['pending_enrollments']) ?></h3>
                <p>Pending Enrollee</p>
            </div>
            <div class="icon">
                <i class="fas fa-file"></i>
            </div>
        </div>
    </div>

    <!-- Confirmed Enrollee -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= number_format($stats_row['confirmed_enrollments']) ?></h3>
                <p>Confirmed Enrollee</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Enrollment Statistics</h3>
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the canvas element
    const ctx = document.getElementById('enrollmentChart');
    
    if(ctx) {
        // Create the chart
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending Enrollments', 'Confirmed Enrollments'],
                datasets: [{
                    data: [
                        <?= $stats_row['pending_enrollments'] ?>,
                        <?= $stats_row['confirmed_enrollments'] ?>
                    ],
                    backgroundColor: [
                        '#ffc107', // warning color for pending
                        '#28a745'  // success color for confirmed
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error('Could not find enrollment chart canvas');
    }
});
</script> 