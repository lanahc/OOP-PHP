<div class="container mt-4">
    <h3>Library Test Panel</h3>
    
    <!-- FontAwesome Test -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>FontAwesome Test</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <i class="fas fa-child fa-3x text-primary"></i>
                    <p>Child Icon</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-user-friends fa-3x text-success"></i>
                    <p>Babysitters Icon</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-calendar-check fa-3x text-warning"></i>
                    <p>Enrollment Icon</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-chart-pie fa-3x text-danger"></i>
                    <p>Statistics Icon</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Test -->
    <div class="card">
        <div class="card-header">
            <h5>Chart.js Test</h5>
        </div>
        <div class="card-body">
            <canvas id="testChart" style="height: 200px;"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Test if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded!');
        alert('Chart.js is not loaded properly!');
    } else {
        console.log('Chart.js is loaded successfully!');
        
        // Create a test chart
        const ctx = document.getElementById('testChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green'],
                datasets: [{
                    label: 'Test Data',
                    data: [12, 19, 3, 5],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});

// Test if FontAwesome is loaded
window.addEventListener('load', function() {
    const testIcon = document.querySelector('.fas');
    const computedStyle = window.getComputedStyle(testIcon, '::before');
    
    if (computedStyle.content === '') {
        console.error('FontAwesome is not loaded!');
        alert('FontAwesome is not loaded properly!');
    } else {
        console.log('FontAwesome is loaded successfully!');
    }
});
</script> 