<?php
// This will be included in your home.php or index.php in admin
$stats = $conn->query("SELECT 
    (SELECT COUNT(*) FROM children WHERE delete_flag = 0) as total_children,
    (SELECT COUNT(*) FROM babysitters WHERE delete_flag = 0) as total_babysitters,
    (SELECT COUNT(*) FROM enrollments WHERE status = 'pending' AND delete_flag = 0) as pending_enrollments,
    (SELECT COUNT(*) FROM enrollments WHERE status = 'confirmed' AND delete_flag = 0) as confirmed_enrollments
");
$stats_row = $stats->fetch_assoc();
?>

<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-child"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Children</span>
                <span class="info-box-number"><?= number_format($stats_row['total_children']) ?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-friends"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Babysitters</span>
                <span class="info-box-number"><?= number_format($stats_row['total_babysitters']) ?></span>
            </div>
        </div>
    </div>
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

<script>
$(function(){
    var enrollmentChartCanvas = $('#enrollmentChart').get(0).getContext('2d')
    var enrollmentData = {
        labels: ['Pending', 'Confirmed'],
        datasets: [{
            data: [
                <?= $stats_row['pending_enrollments'] ?>,
                <?= $stats_row['confirmed_enrollments'] ?>
            ],
            backgroundColor: ['#ffc107', '#28a745']
        }]
    }
    var enrollmentChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            position: 'bottom'
        }
    }
    new Chart(enrollmentChartCanvas, {
        type: 'doughnut',
        data: enrollmentData,
        options: enrollmentChartOptions
    })
})
</script> 