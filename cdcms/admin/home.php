<?php
require_once('../config.php');
require_once('../classes/DBConnection.php');
require_once('../classes/SystemSettings.php');

if(!isset($_settings)){
    $sys = new SystemSettings();
    $sys->set_userdata('system_info');
    $settings = $sys->load_system_info();
    $_settings = json_decode($settings);
}

?>

<h1>Welcome to <?php echo $_settings->info('name') ?></h1>
<hr class="border-info">
<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lgy-3">
        <div class="info-box bg-white shadow-lg rounded-lg hover-effect">
            <span class="info-box-icon bg-gradient-info elevation-2 rounded-circle">
                <i class="fas fa-th-list"></i>
            </span>
            <div class="info-box-content p-3">
                <span class="info-box-text text-muted">Service List</span>
                <span class="info-box-number text-dark fw-bold">
                    <?php echo $conn->query("SELECT * FROM `service_list` where status = 1")->num_rows; ?>
                </span>
                <div class="progress" style="height: 3px;">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-gradient-dark elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
            <span class="info-box-text">Babysitters List</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `babysitter_list` where `status` = 1")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-gradient-secondary elevation-1"><i class="fas fa-file-alt"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Pending Enrollee</span>
            <span class="info-box-number text-right">
                <?php 
                    $pending = $conn->query("SELECT * FROM `enrollment_list` where `status` = 0")->num_rows;
                    echo $pending;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-file-alt"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Confirmed Enrollee</span>
            <span class="info-box-number text-right">
                <?php 
                    $confirmed = $conn->query("SELECT * FROM `enrollment_list` where `status` = 1")->num_rows;
                    echo $confirmed;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>

<!-- Enrollment Statistics Chart -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-outline card-primary shadow">
            <div class="card-header">
                <h3 class="card-title">Enrollment Statistics</h3>
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('enrollmentChart');
    if(ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending Enrollments', 'Confirmed Enrollments'],
                datasets: [{
                    data: [<?php echo $pending ?>, <?php echo $confirmed ?>],
                    backgroundColor: [
                        '#6c757d',  // matches bg-gradient-secondary
                        '#007bff'   // matches bg-gradient-primary
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
                                family: "Times New Roman",
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        titleFont: {
                            family: "Times New Roman",
                            size: 16
                        },
                        bodyFont: {
                            family: "Times New Roman",
                            size: 14
                        }
                    }
                }
            }
        });
    }
});
</script>