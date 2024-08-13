<?php
session_start(); //start the session 

require_once 'vendor/autoload.php';
include 'admin/db_connect.php'; //include the database connection

$db = $conn; //connect to the database

// store client id , client secret and redirect url
$client_id = '29684770506-j13ciqdslc2hvdsr5icrpsb0gc6t1qdh.apps.googleusercontent.com';
$client_secret = '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';

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$base_url = $protocol . $_SERVER["HTTP_HOST"];
$login_url = $base_url . '/login.php';

if ($_SERVER["HTTP_HOST"] == 'localhost') {
    $redirect_uri = $login_url;
} else {
    $redirect_uri = 'http://www.pakmalausatay.live/login.php';
}

// starting the google client
$client = new Google_Client();

//configuring the client
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("email"); // email and profile are the scopes required for login
$client->addScope("profile");

if (isset($_GET['code'])) { //TAKE VALUE THAT PROVIDED FROM GOOGLE

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']); //get the token

    if (!isset($token['error'])) {

        //take access token from token
        $client->setAccessToken($token['access_token']);

        //starting google service ouath2
        $service = new Google_Service_Oauth2($client); //get the data from google

        //get user data
        $profile = $service->userinfo->get();

        //store response data from google account to variable
        $g_name = $profile['name'];
        $g_email = $profile['email'];
        $g_picture = $profile['picture'];
        $g_id = $profile['id'];

        //store the data to session
        $_SESSION['g_name'] = $g_name; //store the name to session
        $_SESSION['g_email'] = $g_email;
        $_SESSION['g_picture'] = $g_picture;
        $_SESSION['g_id'] = $g_id;

        //check if the user already registered. If not, register the user
        $d = $db->query("SELECT * FROM user_info where email = '" . $g_email . "' ");

        if ($d->num_rows > 0) {
            $result = $d->fetch_array();
            foreach ($result as $key => $value) { //$key is the index of the array, $value is the value of the array and result is the array
                if ($key != 'password' && !is_numeric($key))
                    $_SESSION['login_' . $key] = $value;
                    $_SESSION['user_role'] = 'customer';
            }
            $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
            $db->query("UPDATE cart set user_id = '" . $_SESSION['login_user_id'] . "' where client_ip ='$ip' ");
            $db->query("UPDATE user_info set oauth_id = '" . $g_id . "' where email = '" . $g_email . "' ");
            //last login
            $db->query("UPDATE user_info set last_login = now() where email = '" . $g_email . "' ");
            header('location:index.php?page=home');
        } else {
            // Use prepared statement to prevent SQL injection
            $save = $db->prepare("INSERT INTO user_info (first_name, email, password, oauth_id) VALUES (?, ?, '', ?)");
            $save->bind_param("sss", $g_name, $g_email, $g_id);
            $save->execute();
            $save->close();

            // Retrieve necessary data only
            $d = $db->query("SELECT user_id, first_name, last_name, email, walletbalance FROM user_info WHERE email = '" . $g_email . "' LIMIT 1");

            if ($d && $d->num_rows > 0) {
                $result = $d->fetch_assoc();

                // Assign retrieved data to session variables
                $_SESSION['login_user_id'] = $result['user_id'];
                $_SESSION['login_first_name'] = $result['first_name'];
                $_SESSION['login_last_name'] = $result['last_name'];
                $_SESSION['login_email'] = $result['email'];
                $_SESSION['login_img_user'] = $g_picture;
                $_SESSION['login_walletbalance'] = $result['walletbalance'];

                $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

                // Update cart with user_id
                $db->query("UPDATE cart SET user_id = '" . $_SESSION['login_user_id'] . "' WHERE client_ip = '$ip' ");

                // Update last login time
                $db->query("UPDATE user_info SET last_login = NOW() WHERE email = '" . $g_email . "' ");

                // Redirect to home page
                header('location:index.php?page=home');
                exit; // Terminate script execution after redirection
            }
        }
    }
}
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

<div class="container" >
    <div class="card p-4 shadow">

        <h3 class="text-center mb-4">Login</h3>
        <form action="" id="login-frm">
            <div class="form-group">
                <label for="email" class="control-label"><span style="color:red;">*</span>Email</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="email" name="email" required="" class="form-control" placeholder="Enter your email">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="control-label"><span style="color:red;">*</span>Password</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" name="password" required="" class="form-control" placeholder="Enter your password" id="password">
                    <div class="input-group-append">
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                <small class="d-flex justify-content-between mt-2">
                    <a href="javascript:void(0)" class="text-dark" id="new_account">Create New Account</a>
                    <a href="forgotpassword.php" class="">Forgot Your Password?</a>
                </small>
            </div>
            <button class="btn btn-dark btn-block animated-button">Login</button>
            <hr>
            <a href="<?php echo $client->createAuthUrl() ?>" class="btn btn-light btn-block animated-button">
                <img src="images/googleicon.png" alt="Google" class="google_light_image mr-2" style="width: 20px;">
                Login with Google
            </a>
        </form>
    </div>
</div>

<style>
    @keyframes button-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 0, 0, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
        }
    }

    .animated-button {
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .animated-button:hover {
        animation: button-pulse 1.5s infinite;
    }

    #uni_modal .modal-footer {
        display: none;
    }

    .google_light_image {
        width: 20px;
        margin-right: 10px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    $('#new_account').click(function() {
        uni_modal("Create an Account", 'signup.php?redirect=index.php?page=checkout')
    })
    $('#login-frm').submit(function(e) {
        e.preventDefault()
        $('#login-frm button[type="submit"]').attr('disabled', true).html('Logging in...');
        if ($(this).find('.alert-danger').length > 0)
            $(this).find('.alert-danger').remove();
        $.ajax({
            url: 'admin/ajax.php?action=login2',
            method: 'POST',
            data: $(this).serialize(), //serialize - encode a set of form elements as a string for submission
            error: err => {
                console.log(err)
                $('#login-frm button[type="submit"]').removeAttr('disabled').html('Login');
            },
            success: function(resp) {
                if (resp == 1) {
                    location.href = '<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php?page=home' ?>';
                } else {
                    $('#login-frm').prepend('<div class="alert alert-danger">Email or password is incorrect.</div>') //prepend - insert content at the beginning of the selected elements
                    $('#login-frm button[type="submit"]').removeAttr('disabled').html('Login');
                }
            }
        })
    })

    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>
