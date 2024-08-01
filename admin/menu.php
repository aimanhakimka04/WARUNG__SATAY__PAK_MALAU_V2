<?php include('db_connect.php'); //

?>
<?php include('header.php');?>
<? include('footeradmin.php');?>

<div class="container-fluid">
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<form action="" id="manage-menu">
					<div class="card">
						<div class="card-header">
						<?php
									if (isset($_GET['category_id']) && $_GET['category_id'] == 'all') {
										$cat = $conn->query("SELECT * FROM category_list order by name asc ");
									} else if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
										$cat = $conn->query("SELECT * FROM category_list where id = {$_GET['category_id']} order by name asc ");
									}
									$row = $cat->fetch_assoc();
									?>
								
							Menu Form ~ <?php echo isset($row['name']) ? $row['name'] : 'All' ?>
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label"><span style="color:red;">*</span>Menu Name</label>
								<input type="text" class="form-control" name="name" required>
							</div>
							<div class="form-group">
								<label class="control-label"><span style="color:red;">*</span>Menu Description</label>
								<textarea cols="30" rows="3" class="form-control" name="description" required></textarea>
							</div>
							<div class="form-group">
								<div class="custom-control custom-switch">
									<input type="checkbox" name="status" class="custom-control-input" id="availability" checked>
									<label class="custom-control-label" for="availability">Available</label>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Category </label>
								
									
									<?php
									if (isset($_GET['category_id']) && $_GET['category_id'] == 'all') {
										$cat = $conn->query("SELECT * FROM category_list order by name asc ");
									} else if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
										$cat = $conn->query("SELECT * FROM category_list where id = {$_GET['category_id']} order by name asc ");
									}
									while ($row = $cat->fetch_assoc()) :
									?>
										<input type="text" class="form-control" name="name" value="<?php echo $row['name'] ?>" disabled>
										<input type="hidden" name="category_id" value="<?php echo $row['id'] ?>">
										
									<?php endwhile; ?>
								
							</div>
 

							<div class="form-group">
								<label class="control-label"><span style="color:red;">*</span>Price</label>
								<input type="text" class="form-control text-right" name="price" step="any" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/^(\d*\.\d{0,2}).*/, '$1');" required>
							</div>
							<div class="form-group">
								<label class="control-label"><span style="color:red;">*</span>Quantity</label>
								<input type="text" class="form-control text-right" name="quantity" step="1" min="0" oninput="this.value = this.value.replace(/\D/g, '').replace(/^0+/, '')" required>
								</div>



							<div class="form-group">
								<label for="" class="control-label"><span style="color:red;">*</span>Image</label>
								<input type="file" class="form-control" name="img" onchange="displayImg(this,$(this))" required>
							</div>
							<div class="form-group">
								<img src="<?php echo isset($image_path) ? '../assets/img/' . $cover_img : '' ?>" alt="" id="cimg">
							</div>
						</div>

						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
									<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-menu').get(0).reset()"> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Img</th>
									<th class="text-center">Room</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
								
								// Assuming $conn represents your database connection
								if ($category_id !== null && is_numeric($category_id)) {
									// Sanitize the input to prevent SQL injection (assuming you're using MySQLi)
									$category_id = $conn->real_escape_string($category_id);

									// Construct the SQL query with a WHERE clause to filter by category ID
									$sql = "SELECT p.*, c.name AS cat 
											FROM product_list p 
											INNER JOIN category_list c ON c.id = p.category_id 
											WHERE p.category_id = $category_id
											ORDER BY p.id ASC";

									// Execute the query
									$cats = $conn->query($sql);
								}else if($category_id == 'all'){
									// Construct the SQL query without a WHERE clause to fetch all products
									$sql = "SELECT p.*, c.name AS cat 
											FROM product_list p 
											INNER JOIN category_list c ON c.id = p.category_id 
											ORDER BY p.id ASC";

									// Execute the query
									$cats = $conn->query($sql);
								}
								 else {
									// Handle invalid or missing category ID here, or provide a default behavior
									echo "Invalid category ID";
								}
								while ($row = $cats->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>


										<td class="text-center">
											<img src="<?php echo isset($row['img_path']) ? '../assets/img/' . $row['img_path'] : '' ?>" alt="" id="cimg">
										</td>
										<td class="">
											<p>Name : <b><?php echo $row['name'] ?></b></p>
											<p>Category : <b><?php echo $row['cat'] ?></b></p>
											<p>Description : <b class="truncate"><?php echo $row['description'] ?></b></p>
											<p>Price : <b><?php echo "RM" . number_format($row['price'], 2) ?></b></p>
											<p>Quantity : <b><?php echo $row['quantity'] ?></b></p>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-primary edit_menu" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-status="<?php echo $row['status'] ?>" data-description="<?php echo $row['description'] ?>" data-price="<?php echo $row['price'] ?>  " data-quantity="<?php echo $row['quantity'] ?>   " data-category_id="<?php echo $row['category_id'] ?>" data-img_path="<?php echo $row['img_path'] ?>">Edit</button>
											<button class="btn btn-sm btn-danger delete_menu" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>

</div>


<style>
	img#cimg,
	.cimg {
		max-height: 10vh;
		max-width: 6vw;
	}

	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset !important;
	}

	.custom-switch,
	.custom-control-input,
	.custom-control-label {
		cursor: pointer;
	}

	b.truncate {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
		font-size: small;
		color: #000000cf;
		font-style: italic;
	}
</style>
<script>
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}

	$('#manage-menu').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_menu',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully added", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				} else if (resp == 2) {
					alert_toast("Data successfully updated", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
				else if (resp == 3) {
					alert("Menu already exist.");
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	})

	$('.edit_menu').click(function() {
		start_load()
		var cat = $('#manage-menu')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		cat.find("[name='price']").val($(this).attr('data-price'))
		cat.find("[name='quantity']").val($(this).attr('data-quantity'))

		cat.find("[name='category_id']").val($(this).attr('data-category_id'))
		if ($(this).attr('data-status') == 1)
			$('#availability').prop('checked', true)
		else
			$('#availability').prop('checked', false)

		cat.find("#cimg").attr('src', '../assets/img/' + $(this).attr('data-img_path'))

		// Remove required attribute from image input when editing
		cat.find("[name='img']").removeAttr('required')

		end_load()
	})

	$('.delete_menu').click(function() {
		_conf("Are you sure to delete this menu?", "delete_menu", [$(this).attr('data-id')])
	})

	function delete_menu($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_menu',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>
