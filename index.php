<?php ob_start();
//include the configuration file
$_SESSION['wallettopup'] = false;
require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_SANDBOX ? PAYPAL_SANDBOX_CLIENT_ID : PAYPAL_PROD_CLIENT_ID; ?>&currency=<?php echo $currency; ?>"></script>

<?php
session_start();
include('header.php');
include('admin/db_connect.php');

$query = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
foreach ($query as $key => $value) {
  if (!is_numeric($key))
    $_SESSION['setting_' . $key] = $value;
}
?>

<style>
  header.masthead {
    background: url(assets/img/<?php echo $_SESSION['setting_cover_img'] ?>);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    position: relative;
    height: 85vh !important;
  }

  header.masthead:before {
    content: "";
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    backdrop-filter: brightness(0.8);
  }
  
  #mainNav .nav-link {
    color: #000 !important; text-shadow: none !important;
    width: 100% !important;
  }

  


</style>

<body id="page-top">
  <!-- Navigation-->
  <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-white">
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container">
      <a class="" style="color: #000 !important; text-shadow: none !important; width: 240px !important;" href="./"><?php echo $_SESSION['setting_name'] ?></a> 
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse"   id="navbarResponsive">
        <ul class="navbar-nav ml-auto my-2 my-lg-0">
          <li class="nav-item"><a class="nav-link js-scroll-trigger" style="width:70px !important;" href="index.php?page=home";>Home</a></li>
          <li class="nav-item"><a class="nav-link js-scroll-trigger" style="width:70px !important;" href="index.php?page=menu">Menu</a></li>
          <li class="nav-item"><a class="nav-link js-scroll-trigger"style="width:70px !important;"   href="index.php?page=about">About</a></li>
          <?php if (isset($_SESSION['login_user_id'])) : ?>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" style="width:120px !important;" href="index.php?page=user_profile">User Profile</a></li>
            <!--Ewallet By Warung Pak Malau-->
            <li class="nav-item"><a class="nav-link js-scroll-trigger" style="width:100px !important;"  href="index.php?page=ewalletpakmalau">E-Wallet</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger"  style="width:70px !important;" href="index.php?page=order_history">History</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger"  style="width:120px !important;" href="index.php?page=cart_list"><span> <span class="badge badge-danger item_count">0</span> <i><img src="images\cart.png" style="height:30px;width:30px;" alt="cart"></i> </span>Cart</a></li>


            <li class="nav-item"><a class="nav-link js-scroll-trigger"  style="width:200px !important;" href="admin/ajax.php?action=logout2"><?php echo $_SESSION['login_first_name'] . ' ' . $_SESSION['login_last_name'] ?> <i><img src="images\poweroffbtn.png" style="height:30px;width:30px;" alt="Logout"></i></a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" ></a> <?php
                                    // Check if login_img_user is null or empty
                                    if (empty($_SESSION['login_img_user'])) {
                                        // If it's null or empty, display user.png
                                        $user_image = "user.png";
                                    } else {
                                        // If it's not null or empty, use the value of login_img_user
                                        $user_image = $_SESSION['login_img_user'];
                                    }
                                    ?>
                                    <img src="assets/img/<?php echo $user_image; ?>" class="userimg" name="userimg" style="width: 50px; height: 50px; border-radius:50px;"></a></li>            
          <?php else : ?>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="javascript:void(0)" id="login_now">Login</a></li>
            <!--<li class="nav-item"><a class="nav-link js-scroll-trigger" href="./admin">Admin Login</a></li>-->
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <style>
    .myScope * {
      /* Reset styles */
      box-sizing: content-box;
      text-shadow: none;
      text-transform: none;
      letter-spacing: normal;
      word-spacing: normal;
      text-indent: 0px;
      text-align: start;
      vertical-align: baseline;
      line-height: normal;
      text-decoration: none;

      color: black;
      background: none;
      margin: 0;
      padding: 0;
      border: 0;

    }
  </style>
  <div class="myScope">
    <!-- Your code here -->
  
    <?php
// Define the list of URLs that should display the paneldelivery section
$allowed_urls = [
    'localhost',
    '3af7-115-132-156-29.ngrok-free.app',
    '15f8-115-132-156-236.ngrok-free.app'
];

// Check if the current host is in the list of allowed URLs and the page parameter is either not set or equals 'home'
if (in_array($_SERVER['HTTP_HOST'], $allowed_urls) && (!isset($_GET['page']) || $_GET['page'] == 'home')) {
// Show the paneldelivery section
    echo '    <div class="paneldelivery" style="background-color:#F8F8F8; display: flex;justify-content:center; height:90px; width:100%; margin-top:70px;">';
} else {
    // Hide the paneldelivery section
    echo '<div class="hidedelivery" style="display: none; height:190px; width:100%; margin-top:70px;">';
}
?>



      <div class="locationdel" style="width:550px">
        <img src="\images\motocycle_delivery.png" style="width:30px; height:30px; padding: 15px;">
        <p style="margin-left:55px; margin-top:-45px ; font-size:12px; ">
          <b>Deliver to</b>
          <br>
          <span id="locationDisplay">Please enter your location</span>
        </p>
        <div style="width: 30px; height: 40px; margin-left: 400px; margin-top: -30px;">
          <a class="choossloc" id="delivery-click">
            <img src="images/arrowDown-vr7.svg" style="width:25px; height:25px;">
          </a>
        </div>
      </div>

      <div class="normalline" style="height:300%; margin-left:20px;  margin-right:20px; width:50px; margin-top:5px;">
        <img src="images/divider_Line-eqn.svg">
      </div>

      <div class="timedeli" style="width:550px">
        <img src="images/clock-hsZ.svg" style="width:30px; height:30px; padding: 15px;">
        <p style="margin-left:55px; margin-top:-45px; font-size:12px;">
          <b>Date & Time</b>
          <br>
          <span id="dateDisplay">Please select date & time</span>

          
        </p>
        <div style="width: 30px; height: 40px; margin-left: 400px; margin-top: -30px;">
          <a class="choosetime" id="date-click">
            <img src="images/arrowDown-vr7.svg" style="width:25px; height:25px;">
          </a>
        </div>
        </button>
      </div>
    </div>
  </div>
  <script>
  //run code on start page
  window.onload = function() {
    //get user location
    let userLocation = localStorage.getItem("location");

    if (userLocation) {
      document.getElementById('locationDisplay').textContent = userLocation;
    }

    let date = localStorage.getItem("selectedDate");
    let time = localStorage.getItem("selectedTime");

    if (localStorage.getItem("selectedDate") && localStorage.getItem("selectedTime")) {
      document.getElementById('dateDisplay').innerText = date + ' ' + time;
    }
  }
</script>


  <?php
  $page = isset($_GET['page']) ? $_GET['page'] : "home";
  if (isset($_GET['productpage'])) {
    $page = $_GET['productpage'];
    include $page . '.php';
  } else {
    include $page . '.php';
  }

  ?>


  <div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
        </div>
        <div class="modal-body">
          <div id="delete_content"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span class="fa fa-arrow-right"></span>
          </button>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.php') ?>
</body>

<?php $conn->close() ?>

</html>




<script>
  
</script>