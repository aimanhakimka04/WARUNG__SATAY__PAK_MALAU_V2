<?php
// Form handling
include 'admin/db_connect.php'; // Include the database connection
$db = $conn; // Connect to the database
if (isset($_POST["reset_password"], $_GET["token"])) {

    $password = $_POST["password"];
    $password2 = $_POST["confirmpassword"];
    $token = $_GET["token"];
    $passwordPattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/";

    if (!preg_match($passwordPattern, $password)) {
        $error = "Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 numeric digit, 1 symbol, and be at least 8 characters long.";
    } else {

        if ($password == $password2) {
            $fp = $db->query("SELECT * FROM forgot_password where fp_url = '" . $token . "' AND fp_status = 0 ");

            if ($fp->num_rows > 0) {
                $fp = $fp->fetch_assoc(); // Fetch the data from the database
                $password = password_hash($password, PASSWORD_DEFAULT);
                $db->query("UPDATE user_info set password = '" . $password . "' where email = '" . $fp['fp_email'] . "' ");
                $db->query("UPDATE forgot_password set fp_status = 1 where fp_url = '" . $token . "' ");
                $success = "Password changed successfully.";
            } else {
                $error = "Failed to reset password. Please request a new link.";
            }
        } else {
            $error = "Passwords do not match.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Reset Your Password</title>
    <style>
        body {
            background: linear-gradient(to right, #2c3e50, #3498db);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Roboto', sans-serif;
            color: #ecf0f1;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 1s ease-out;
        }
        .card-header {
            background: #2980b9;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-body {
            padding: 2rem;
            background: #34495e;
            color: #ecf0f1;
        }
        .form-label {
            font-weight: bold;
            color: #ecf0f1;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 0.75rem;
            font-size: 1.2rem;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .text-center a {
            color: #3498db;
            transition: color 0.3s ease;
        }
        .text-center a:hover {
            color: #2980b9;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .alert {
            margin-top: 20px;
        }
        .btn-close {
            float: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (isset($success)) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>
                <div class="card">
                    <div class="card-header">
                        Reset Password
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                        <div class="mb-3">
                            <label for="password" class="form-label"><span style="color:red;">*</span> New Password:</label>
                            <input type="password" class="form-control" placeholder="Password" required name="password" id="password" />
                        </div>
                        <div class="mb-3">
                            <label for="confirmpassword" class="form-label"><span style="color:red;">*</span> Confirm Password:</label>
                            <input type="password" class="form-control" placeholder="Confirm Password" required name="confirmpassword" id="confirmpassword" />
                        </div>
                        <div class="mb-3">
                            <input type="checkbox" id="showPasswordToggle" /> Show Passwords
                        </div>

                            <button class="btn btn-primary w-100" name="reset_password">
                                Reset Password
                            </button>
                            <div class="text-center mt-3">
                                <a href="/index.php?page=home">
                                    Back to Login
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>


<script>
    document.getElementById('showPasswordToggle').addEventListener('change', function() {
    var passwordField = document.getElementById('password');
    var confirmPasswordField = document.getElementById('confirmpassword');
    if (this.checked) {
        passwordField.type = 'text';
        confirmPasswordField.type = 'text';
    } else {
        passwordField.type = 'password';
        confirmPasswordField.type = 'password';
    }
});

</script>