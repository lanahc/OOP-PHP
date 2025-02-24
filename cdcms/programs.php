<h1>Our Services/Programs</h1>
<hr class="border-navy bg-navy">
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="input-group mb-4">
                <input type="search" id="search" class="form-control form-control-border" placeholder="Search Program here...">
                <div class="input-group-append">
                    <button type="button" class="btn btn-sm border-0 border-bottom btn-default">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="package-list">
    <?php 
        $package = $conn->query("SELECT * FROM service_list");
        if ($package->num_rows > 0) {
            while($row = $package->fetch_assoc()):
    ?>
        <div class="col-md-6 mb-4 package-item">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0"><?= ucwords($row['name']) ?></h3>
                </div>
                <div class="card-body">
                    <div class="program-details mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <p><i class="fas fa-clock mr-2"></i> Duration: 
                                    <span class="badge badge-info">1 hour</span>
                                </p>
                                <p><i class="fas fa-users mr-2"></i> Capacity: 
                                    <span class="badge badge-secondary">15 students</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="fas fa-calendar-alt mr-2"></i> Schedule: 
                                    <span class="badge badge-success">Mon-Wed-Fri</span>
                                </p>
                                <p><i class="fas fa-star mr-2"></i> Age Group: 
                                    <span class="badge badge-warning">3-4 years</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="program-description">
                        <h5><i class="fas fa-info-circle mr-2"></i>Program Description:</h5>
                        <p class="text-muted"><?= html_entity_decode($row['description']) ?></p>
                    </div>

                    <div class="program-highlights mt-3">
                        <h5><i class="fas fa-check-circle mr-2"></i>Program Highlights:</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success mr-2"></i>Experienced instructors</li>
                            <li class="list-group-item"><i class="fas fa-check text-success mr-2"></i>Age-appropriate activities</li>
                            <li class="list-group-item"><i class="fas fa-check text-success mr-2"></i>Safe learning environment</li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-0">
                                <i class="fas fa-tag mr-2"></i>$<?= number_format($row['price'], 2) ?>
                            </h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php if(isset($_SESSION['login_id'])): ?>
                                <!-- User is logged in - direct to enrollment form -->
                                <a href="<?= base_url ?>?page=enrollment&program_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary enroll-btn">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Enroll Now
                                </a>
                            <?php else: ?>
                                <!-- User is not logged in - direct to auth page -->
                                <a href="<?= base_url ?>?page=auth&program_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary enroll-btn">
                                    <i class="fas fa-user-plus mr-2"></i>Login to Enroll
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php 
            endwhile; 
        } else {
    ?>
        <div class="col-12 text-center">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>No programs listed yet.
            </div>
        </div>
    <?php } ?>
    </div>
    <div id="no_result" style="display:none">
        <div class="alert alert-info text-center">
            <i class="fas fa-search mr-2"></i>No matching programs found.
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
    border-radius: 8px;
}

.card:hover {
    transform: translateY(-5px);
}

.card-header {
    border-radius: 8px 8px 0 0 !important;
}

.badge {
    font-size: 0.9em;
    padding: 8px 12px;
}

.program-highlights ul li {
    padding: 0.5rem 1rem;
}

.enroll-btn {
    border-radius: 20px;
    padding: 8px 20px;
}

.program-description p {
    font-size: 0.95em;
    line-height: 1.6;
}
</style>

<script>
$(function(){
    // Existing search functionality
    $('#search').on("input",function(e){
        var _search = $(this).val().toLowerCase();
        $('#package-list .package-item').each(function(){
            var _txt = $(this).text().toLowerCase();
            if(_txt.includes(_search) === true){
                $(this).show();
            }else{
                $(this).hide();
            }
            if($('#package-list .package-item:visible').length <= 0){
                $("#no_result").show('slow');
            }else{
                $("#no_result").hide('slow');
            }
        });
    });
});
</script>
