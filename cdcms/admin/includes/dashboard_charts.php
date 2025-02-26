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

<!-- Required CSS and JavaScript libraries -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

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
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Enrollment Statistics</h3>
                    <button class="btn btn-primary btn-sm" id="exportButton">
                        <i class="fas fa-download"></i> Export Report
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Wait for all libraries to load
window.onload = function() {
    // Initialize Chart
    const ctx = document.getElementById('enrollmentChart');
    if(ctx) {
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
                        '#ffc107',
                        '#28a745'
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
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
    }

    // Add click event listener to export button
    document.getElementById('exportButton').addEventListener('click', async function() {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Add title
            doc.setFontSize(16);
            doc.text('Kiddie Day Care Management System', 105, 15, { align: 'center' });
            doc.setFontSize(14);
            doc.text('Enrollment Statistics Report', 105, 25, { align: 'center' });
            
            // Add date
            doc.setFontSize(10);
            doc.text('Generated on: ' + new Date().toLocaleString(), 105, 35, { align: 'center' });
            
            // Add statistics
            doc.setFontSize(12);
            doc.text('Dashboard Statistics:', 20, 45);
            doc.setFontSize(10);
            doc.text('Total Services: <?= number_format($stats_row['total_services'] ?? 0) ?>', 25, 55);
            doc.text('Total Babysitters: <?= number_format($stats_row['total_babysitters']) ?>', 25, 62);
            doc.text('Pending Enrollments: <?= number_format($stats_row['pending_enrollments']) ?>', 25, 69);
            doc.text('Confirmed Enrollments: <?= number_format($stats_row['confirmed_enrollments']) ?>', 25, 76);
            
            // Convert chart to image
            const canvas = document.getElementById('enrollmentChart');
            const chartImage = await html2canvas(canvas);
            
            // Add chart image to PDF
            doc.addImage(
                chartImage.toDataURL('image/png'),
                'PNG',
                20,
                85,
                170,
                100
            );
            
            // Save the PDF
            doc.save('kiddie-daycare-statistics.pdf');
            
        } catch (error) {
            console.error('PDF Export Error:', error);
            alert('Error generating PDF. Please check console for details.');
        }
    });
};
</script> 