<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `service_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
    .service-details {
        font-family: "Times New Roman", Times, serif;
        padding: 2rem;
    }
    .service-details dt {
        font-size: 1.1rem;
        color: #182848;
        margin-bottom: 0.5rem;
    }
    .service-details dd {
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
    }
    .badge {
        padding: 8px 15px;
        font-size: 0.9rem;
        border-radius: 20px;
    }
    .btn-dark {
        background: linear-gradient(45deg, #343a40, #1d2124);
        border: none;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }
    .btn-dark:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .program-image-large {
        width: 100%;
        max-width: 300px;
        border-radius: 15px;
        margin-bottom: 20px;
    }
    .program-details {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .schedule-badge {
        background-color: #e3f2fd;
        color: #1976d2;
        padding: 5px 10px;
        border-radius: 15px;
        margin-right: 10px;
        display: inline-block;
        margin-bottom: 5px;
    }
</style>
<div class="container-fluid service-details">
    <?php if(isset($image_path) && !empty($image_path)): ?>
        <img src="<?php echo base_url . $image_path ?>" 
             alt="Program Image" 
             class="program-image-large">
    <?php endif; ?>

    <div class="program-details">
        <h3><?= isset($name) ? $name : '' ?></h3>
        <span class="category-badge category-<?php echo $category ?>">
            <?php echo ucfirst($category) ?>
        </span>
    </div>

    <dl>
        <dt>Price</dt>
        <dd class="pl-4">₱<?php echo number_format($price, 2) ?></dd>

        <dt>Age Group</dt>
        <dd class="pl-4"><?php echo $age_group ?></dd>

        <dt>Duration</dt>
        <dd class="pl-4"><?php echo $duration ?></dd>

        <dt>Available Schedules</dt>
        <dd class="pl-4">
            <?php 
            $schedules = explode(',', $schedule);
            foreach($schedules as $sched):
                echo "<span class='schedule-badge'>" . ucfirst($sched) . "</span>";
            endforeach;
            ?>
        </dd>

        <dt>Program Description</dt>
        <dd class="pl-4">
            <div class="description-content">
                <?= isset($description) ? html_entity_decode($description) : '' ?>
            </div>
        </dd>
        
        <dt>Status</dt>
        <dd class="pl-4">
            <?php
            if(isset($status)):
                switch($status){
                    case '1':
                        echo "<span class='badge badge-success'>Active</span>";
                        break;
                    case '0':
                        echo "<span class='badge badge-secondary'>Inactive</span>";
                        break;
                }
            endif;
            ?>
        </dd>
    </dl>
    <div class="text-right mt-4">
        <button class="btn btn-dark" type="button" data-dismiss="modal">
            <i class="fa fa-times mr-2"></i>Close
        </button>
    </div>
</div>