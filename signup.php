<?php session_start() ?>
<div class="container-fluid">
	<form action="" id="signup-frm">
		<div class="form-group">
			<label for="" class="control-label">Firstname</label>
			<input type="text" name="first_name" required="" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Lastname</label>
			<input type="text" name="last_name" required="" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Contact</label>
			<input type="text" name="mobile" required="" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Address</label>
			<textarea cols="30" rows="3" name="address" required="" class="form-control"></textarea>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Email</label>
			<input type="email" name="email" required="" class="form-control">
		</div>
		<div class="form-group position-relative">
			<label for="" class="control-label">Password</label>
			<div class="input-group">
			<input type="password" name="password" required="" class="form-control password-field" id="password">
			<div class="input-group-append">
				<span class="input-group-text" id="togglePassword" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
			</div>

			</div>	
		</div>
		<div class="form-group position-relative">
			<label for="" class="control-label">Confirm Password</label>
			<div class="input-group">

				<input type="password" name="confirm_password" required="" class="form-control confirm-password-field" id="conpassword">
				<div class="input-group-append">
					<span class="input-group-text" id="toggleconPassword" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
				</div>
			</div>
		</div>
		<button class="btn btn-info btn-lg btn-block" type="submit">Create Account</button>
	</form>
</div>

<style>
	@keyframes fadeInUp {
		from {
			transform: translate3d(0, 40px, 0);
			opacity: 0;
		}

		to {
			transform: translate3d(0, 0, 0);
			opacity: 1;
		}
	}

	.container-fluid {
		background-color: #ffffff;
		border-radius: 10px;
		padding: 20px;
		box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
		animation: fadeInUp 0.5s ease;
		max-width: 500px;
		margin: auto;
		
	}

	.form-group label {
		font-weight: bold;
		color: #555;
	}

	.form-control {
		border-radius: 5px;
		border: 1px solid #ddd;
	}

	.btn-info {
		background-color: #5bc0de;
		border-color: #46b8da;
		transition: background-color 0.3s, border-color 0.3s;
	}

	.btn-info:hover {
		background-color: #31b0d5;
		border-color: #269abc;
	}

	.alert {
		animation: fadeInUp 0.5s ease;
	}

	.field-icon {
		position: absolute;
		right: 15px;
		top: 40px;
		transform: translateY(-50%);
		cursor: pointer;
		color: #aaa;
	}

	.field-icon:hover {
		color: #333;
	}

	#uni_modal .modal-footer {
		display: none;
	}

	.position-relative {
		position: relative;
	}
</style>

<script>
	$(document).ready(function() {
		$('#togglePassword').click(function() {
			const passwordField = $('#password');
			const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
			passwordField.attr('type', type);
			this.querySelector('i').classList.toggle('fa-eye-slash');
		});
		$('#toggleconPassword').click(function() {
			const passwordField = $('#conpassword');
			const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
			passwordField.attr('type', type);
			this.querySelector('i').classList.toggle('fa-eye-slash');
		});

		$('#signup-frm').submit(function(e) {
			e.preventDefault();
			$('#signup-frm button[type="submit"]').attr('disabled', true).html('Saving...');
			if ($(this).find('.alert-danger').length > 0)
				$(this).find('.alert-danger').remove();

			var password = $('[name="password"]').val();
			var confirm_password = $('[name="confirm_password"]').val();
			var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/;

			if (password !== confirm_password) {
				$('#signup-frm').prepend('<div class="alert alert-danger">Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, and one number, and one symbol.</div>');
				$('#signup-frm button[type="submit"]').removeAttr('disabled').html('Create Account');
				return false;
			}

			if (!passwordRegex.test(password)) {
				$('#signup-frm').prepend('<div class="alert alert-danger">Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.</div>');
				$('#signup-frm button[type="submit"]').removeAttr('disabled').html('Create Account');
				return false;
			}

			var mobile = $('[name="mobile"]').val();
			var mobileRegex = /^60\d{8,12}$/;

			if (!mobileRegex.test(mobile)) {
				$('#signup-frm').prepend('<div class="alert alert-danger">Phone number must start with "60" and contain only numbers with a length between 8 and 12 digits.</div>');
				$('#signup-frm button[type="submit"]').removeAttr('disabled').html('Create Account');
				return false;
			}

			$.ajax({
				url: 'admin/ajax.php?action=signup',
				method: 'POST',
				data: $(this).serialize(), //convert the form to a query string
				error: err => {
					console.log(err)
					$('#signup-frm button[type="submit"]').removeAttr('disabled').html('Create Account');
				},
				success: function(resp) {
					if (resp == 1) {
						location.href = '<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php?page=home' ?>';
					} else {
						$('#signup-frm').prepend('<div class="alert alert-danger">Email already exists.</div>')
						$('#signup-frm button[type="submit"]').removeAttr('disabled').html('Create Account');
					}
				}
			})
		});
	});
</script>