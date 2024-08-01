<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db_connect.php'; // Include the database connection
$db = $conn; // Connect to the database

if (isset($_POST["recover-submit"])) {
    $email = $_POST["email"];
    $d = $db->query("SELECT * FROM users WHERE email = '$email'");
    $dateTime = date("Y-m-d H:i:s");

    if ($d->num_rows > 0) {
        $url = hash("sha256", uniqid() . time()); // Generate a unique token
        $db->query("INSERT INTO forgot_password(fp_email, fp_url, fp_requestTime) VALUES ('$email', '$url', '$dateTime')");

        // PHPMailer configuration
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'pakmalauwarungsatay@gmail.com';                 
            $mail->Password   = 'cemjkjawoatwbdct';                         
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
            $mail->Port       = 587;                                    

            //Recipients
            $mail->setFrom('pakmalauwarungsatay@gmail.com', 'Pak Malau Warung Satay');
            $mail->addAddress($email);     

            // Content
            $mail->isHTML(true);                                  
            $mail->Subject = 'Reset Password';
            $message = file_get_contents("forgottemplate.html");

            // Replace placeholders in the email template
            $message = str_replace("{{EMAIL}}", $email, $message);
            $resetUrl = "http://localhost/admin/resetpassadmin.php?token=" . $url;
            $message = str_replace("{{URL}}", $resetUrl, $message);

            $mail->Body = $message;

            $mail->send();
            $success = "Reset password has been sent to your email.";
        } catch (Exception $e) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "This email has not been registered in this system.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style>
        .form-gap {
            padding-top: 70px;
        }
        .alert-success {
            color: black;
            font-weight: bold;
            float: right;
        }
        .alert-danger {
            color: red;
            font-weight: bold;
            float: right;
        }
    </style>
</head>
<body>
<div class="form-gap"></div>
<div class="container">
    <div class="row">
        <?php
        if (isset($success)) {
            echo '<div class="alert alert-success"><strong>Success!</strong> ' . $success . '</div>';
        }
        if (isset($error)) {
            echo '<div class="alert alert-danger"><strong>Error!</strong> ' . $error . '</div>';
        }
        ?>
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        <h3><i class="fa fa-lock fa-4x"></i></h3>
                        <h2 class="text-center">Forgot Password?</h2>
                        <p>You can reset your password here.</p>
                        <div class="panel-body">
                            <form id="register-form" role="form" autocomplete="off" class="form" method="POST" action="">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                        <input id="email" name="email" placeholder="email address" class="form-control" type="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                </div>
                                <input type="hidden" class="hide" name="token" id="token" value="">
                            </form>
                            <div class="">
                                <a href="index.php">Back To Homepage</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
