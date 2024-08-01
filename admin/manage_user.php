<?php 
include('db_connect.php');
if(isset($_GET['id'])){
    $user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-user">
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
        <div class="form-group">
            <label for="name"><span style="color:red;">*</span>Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required pattern="[A-Za-z ]+" title="Name can only contain letters (a-z, A-Z)">
        </div>
        <div class="form-group">
            <label for="username"><span style="color:red;">*</span>Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required pattern="[A-Za-z0-9]+" title="Username can only contain letters (a-z, A-Z) and numbers (0-9)">
        </div>
        <div class="form-group">
    <label for="password"><span style="color:red;">*</span>Password</label>
    <div class="input-group">
        <input type="password" name="password" id="password" class="form-control" value="" required
            pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}"
            title="Password must be at least 8 characters long and include one lowercase letter, one uppercase letter, one number, and one special character."
            data-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                Show
            </button>
        </div>
    </div>
    <div class="invalid-feedback">
        Password must be at least 8 characters long and include one lowercase letter, one uppercase letter, one number, and one special character.
    </div>
</div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" class="custom-select">
                <option value="1" <?php echo isset($meta['gender']) && $meta['gender'] == 1 ? 'selected': '' ?>>Male</option>
                <option value="2" <?php echo isset($meta['gender']) && $meta['gender'] == 2 ? 'selected': '' ?>>Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="phone_no"><span style="color:red;">*</span>Phone Number</label>
            <input type="text" name="phone_no" id="phone_no" class="form-control" value="<?php echo isset($meta['phone_no']) ? $meta['phone_no']: '' ?>" required pattern="^\d{8,12}$" title="Phone number must contain only numbers with a length between 8 and 12 digits.">
        </div>
        <div class="form-group">
            <label for="email"><span style="color:red;">*</span>Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com$" title="Please enter a valid @gmail.com email address" placeholder="example@gmail.com">
        </div>
        <div class="form-group">
            <label for="type"><span style="color:red;">*</span>User Type</label>
            <select name="type" id="type" class="custom-select">
                <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Admin</option>
                <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Staff</option>
            </select>
        </div>
        <!---button type="submit" class="btn btn-primary">Save</button--->
    </form>
</div>

<script>
    document.getElementById('toggle-password').addEventListener('click', function () {
    var passwordInput = document.getElementById('password');
    var passwordToggle = document.getElementById('toggle-password');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.innerText = 'Hide';
    } else {
        passwordInput.type = 'password';
        passwordToggle.innerText = 'Show';
    }
});


    $('#manage-user').submit(function(e){
    e.preventDefault();
    var form = $(this);
    var isValid = true;

    // Disable submit button and show "Saving..." text
    form.find('button[type="submit"]').attr('disabled', true).html('Saving...');

    // Remove any existing error alerts
    if(form.find('.alert-danger').length > 0)
        form.find('.alert-danger').remove();

    var password = $('[name="password"]').val();
    var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/;

    // Validate password
    if (!passwordRegex.test(password)) {
        form.prepend('<div class="alert alert-danger">Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.</div>');
        form.find('button[type="submit"]').removeAttr('disabled').html('Save');
        isValid = false;
    }

   

    var name = $('[name="name"]').val();
    var nameRegex = /^[A-Za-z ]+$/;

    // Validate name
    if (!nameRegex.test(name)) {
        form.prepend('<div class="alert alert-danger">Name can only contain letters (a-z, A-Z).</div>');
        form.find('button[type="submit"]').removeAttr('disabled').html('Save');
        isValid = false;
    }

    var username = $('[name="username"]').val();
    var usernameRegex = /^[A-Za-z0-9]+$/;

    // Validate username
    if (!usernameRegex.test(username)) {
        form.prepend('<div class="alert alert-danger">Username can only contain letters (a-z, A-Z) and numbers (0-9).</div>');
        form.find('button[type="submit"]').removeAttr('disabled').html('Save');
        isValid = false;
    }

    var phone_no = $('[name="phone_no"]').val();
    var phoneNoRegex = /^\d{8,12}$/;

    // Validate phone number\
    if (!phoneNoRegex.test(phone_no)) {
        form.prepend('<div class="alert alert-danger">Phone number must contain only numbers with a length between 8 and 12 digits.</div>');
        form.find('button[type="submit"]').removeAttr('disabled').html('Save');
        isValid = false;
    }

    var email = $('[name="email"]').val();
    var emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

    // Validate email
    if (!emailRegex.test(email)) {
        form.prepend('<div class="alert alert-danger">Please enter a valid @gmail.com email address.</div>');
        form.find('button[type="submit"]').removeAttr('disabled').html('Save');
        isValid = false;
    }

    // If any validation fails, prevent form submission
    if (!isValid) {
        return false;
    }

    // Log the serialized form data to console for debugging
    console.log(form.serialize());

    // If all validations pass, proceed with form submission
    start_load();
    $.ajax({
    url: 'ajax.php?action=save_user',
    method: 'POST',
    data: form.serialize(),
    success: function(resp) {
        console.log(resp); // Log the response for debugging

        if (resp == 1) {
            alert_toast("Data successfully saved", 'success');
            setTimeout(function() {
                location.reload();
            }, 1500);
        } else if (resp == -2) {
            form.prepend('<div class="alert alert-danger">Username already exists.</div>');
        } else if (resp == -3) {
            form.prepend('<div class="alert alert-danger">Phone number already exists.</div>');
        } else if (resp == -4) {
            form.prepend('<div class="alert alert-danger">Email already exists.</div>');
        } else {
            form.prepend('<div class="alert alert-danger">Failed to save data. Please try again later.</div>');
        }

        form.find('button[type="submit"]').removeAttr('disabled').html('Save');
    }
});


});


    document.getElementById('gender').addEventListener('change', function() {
        var gender = this.value;
        var userTypeSelect = document.getElementById('type');
        var riderOption = userTypeSelect.querySelector('option[value="3"]');
        
        if (gender !== '1') { // Not Male
            riderOption.style.display = 'none'; // Hide Rider option
        } else {
            riderOption.style.display = ''; // Show Rider option
        }
    });
</script>
