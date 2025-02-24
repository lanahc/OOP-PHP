<style>
    .img-avatar{
        width:45px;
        height:45px;
        object-fit:cover;
        object-position:center center;
        border-radius:100%;
    }
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 15px !important;
    }
    .card-header {
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }
    .table {
        font-family: "Times New Roman", Times, serif;
    }
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    .badge {
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    .badge-success {
        background: linear-gradient(45deg, #28a745, #20c997);
    }
    .badge-secondary {
        background: linear-gradient(45deg, #6c757d, #495057);
    }
    .btn-primary {
        background: linear-gradient(45deg, #4b6cb7, #182848);
        border: none;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .dropdown-menu {
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .dropdown-item {
        padding: 8px 20px;
        transition: all 0.2s ease;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    .program-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
    }
    .category-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
    }
    .category-daycare { background-color: #e3f2fd; color: #1976d2; }
    .category-preschool { background-color: #f3e5f5; color: #7b1fa2; }
    .category-afterschool { background-color: #e8f5e9; color: #388e3c; }
    .category-special { background-color: #fff3e0; color: #f57c00; }
    .price-column { font-weight: bold; color: #2196f3; }
</style>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title">Programs & Services</h3>
		<div class="card-tools">
			<button id="create_new" class="btn btn-primary">
				<i class="fas fa-plus-circle mr-2"></i>Add New Program
			</button>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Image</th>
						<th>Category</th>
						<th>Program Name</th>
						<th>Price</th>
						<th>Age Group</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT * from `service_list` order by `category`, `name` asc");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td>
								<img src="<?php echo base_url . $row['image_path'] ?>" 
									 alt="Program Image" 
									 class="program-image">
							</td>
							<td>
								<span class="category-badge category-<?php echo $row['category'] ?>">
									<?php echo ucfirst($row['category']) ?>
								</span>
							</td>
							<td><?php echo ucwords($row['name']) ?></td>
							<td class="price-column">₱<?php echo number_format($row['price'], 2) ?></td>
							<td><?php echo $row['age_group'] ?></td>
							<td class="text-center">
								<?php
									switch($row['status']){
										case '1':
											echo "<span class='badge badge-success'>Active</span>";
											break;
										case '0':
											echo "<span class='badge badge-secondary'>Inactive</span>";
											break;
									}
								?>
							</td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('#create_new').click(function(){
			uni_modal("Add New Service/Program","services/manage_service.php", 'mid-large')
		})
        $('.edit_data').click(function(){
			uni_modal("Update Service Details","services/manage_service.php?id="+$(this).attr('data-id'), 'mid-large')
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Service permanently?","delete_service",[$(this).attr('data-id')])
		})
		$('.view_data').click(function(){
			uni_modal("Service Details","services/view_service.php?id="+$(this).attr('data-id'), 'mid-large')
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });

		$("#uni_modal").on('show.bs.modal',function(e){
			$('.summernote').summernote({
		        height: '15vh',
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		            [ 'table', [ 'table' ] ],
					['insert', ['link', 'picture']],
		            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
		        ]
		    })
		})
	})
	function delete_service($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_service",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>