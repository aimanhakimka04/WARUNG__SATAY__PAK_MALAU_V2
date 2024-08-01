<?php ob_start(); ?>
<?php
session_start();
if (!isset($_SESSION['login_id']))
    header('location:login.php');



include 'db_connect.php';


$category= $conn->query("SELECT * FROM category_list order by id asc ");

?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Satay Pak Malau</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">


    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">


    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>PAK MALAU SATAY</title>
        <meta name="description" content="WARUNG SATAY PAK MALAU">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-icon.png">
        <link rel="shortcut icon" href="favicon.ico">


        <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
        <link rel="stylesheet" href="vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">

        <link rel="stylesheet" href="assetsnew/css/style.css">

        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    </head>

    <link rel="stylesheet" href="assetsnew/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>

<body>
    <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="index.php?page=dashboard">Warung Satay Pak Malau-Admin</a>
                <a class="navbar-brand hidden" href="./"><img src="images/logo2.png" alt="Logo"></a>1
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.php?page=dashboard"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <?php if($_SESSION['login_type'] == 1): ?>
                    <li>
                        <a href="index.php?page=users"> <i class="menu-icon fa fa-laptop"></i>User </a>
                    </li>
                    <?php endif; ?>
                    <h3 class="menu-title">Products</h3><!-- /.menu-title -->
                    <li class="menu-item-has-children dropdown">
                        <a href="index.php?page=menu&category_id=all" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-laptop"></i>Product Menu</a>
                   
                        <ul class="sub-menu children dropdown-menu">
                        <?php while($row=$category->fetch_assoc()): ?>
                            <li><i class="fa fa-table"></i><a href="index.php?page=menu&category_id=<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></li>

                        <?php endwhile; ?>
                        </ul></a>
                    </li>
                    <li >
                    <a href="index.php?page=categories"> <i class="menu-icon ti-email"></i>Product categories</a>
                    </li>
                    

                    <h3 class="menu-title">Customer </h3><!-- /.menu-title -->

                   
                    <li>
                        <a href="index.php?page=orders"> <i class="menu-icon ti-email"></i>Customer Order (Food)</a>
                    </li>
                   
                    <li>
                        <a href="index.php?page=customer_list"> <i class="menu-icon ti-email"></i>Customer List </a>
                    </li>
                    <li>
                        <a href="index.php?page=comment_review"> <i class="menu-icon ti-email"></i>Comment Review </a>
                    </li>
                   
            
                    <h3 class="menu-title">Other Menu</h3><!-- /.menu-title -->
                    <?php if($_SESSION['login_type'] == 1): ?>
                    <li>
                        <a href="index.php?page=site_settings"> <i class="menu-icon ti-email"></i>Site Settings </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="index.php?page=voucher"> <i class="menu-icon ti-email"></i>Voucher </a>
                    </li>
                    <?php if($_SESSION['login_type'] == 2): ?>
                    <li>
                        <a href="../Delivery/index.php"> <i class="menu-icon ti-email"></i>Delivery Page</a>
                    </li>
                    <?php endif; ?>
                   
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">
                        

       
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="float-right">
                        <a href="ajax.php?action=logout" class=""  >
                            <img class="user-avatar rounded-circle" src="../images/poweroffbtn.png" alt="User Avatar">
                        </a>

                    
                    </div>



                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->
        <?php $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; ?>
        <?php include $page . '.php' ?>

                            
  <div id="preloader"></div>
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

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





</body>

</html>

<?php include 'footeradmin.php'; ?>


<script>
    window.start_load = function() {
        $('body').prepend('<di id="preloader2"></di>')
    }
    window.end_load = function() {
        $('#preloader2').fadeOut('fast', function() {
            $(this).remove();
        })
    }

    window.uni_modal = function($title = '', $url = '') {
        start_load()
        $.ajax({
            url: $url,
            error: err => {
                console.log()
                alert("An error occured")
            },
            success: function(resp) {
                if (resp) {
                    $('#uni_modal .modal-title').html($title)
                    $('#uni_modal .modal-body').html(resp)
                    $('#uni_modal').modal('show')
                    end_load()
                }
            }
        })
    }
    window._conf = function($msg = '', $func = '', $params = []) {
        $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")")
        $('#confirm_modal .modal-body').html($msg)
        $('#confirm_modal').modal('show')
    }
    window.alert_toast = function($msg = 'TEST', $bg = 'success') {
        $('#alert_toast').removeClass('bg-success')
        $('#alert_toast').removeClass('bg-danger')
        $('#alert_toast').removeClass('bg-info')
        $('#alert_toast').removeClass('bg-warning')

        if ($bg == 'success')
            $('#alert_toast').addClass('bg-success')
        if ($bg == 'danger')
            $('#alert_toast').addClass('bg-danger')
        if ($bg == 'info')
            $('#alert_toast').addClass('bg-info')
        if ($bg == 'warning')
            $('#alert_toast').addClass('bg-warning')
        $('#alert_toast .toast-body').html($msg)
        $('#alert_toast').toast({
            delay: 3000
        }).toast('show');
    }
    $(document).ready(function() {
        $('#preloader').fadeOut('fast', function() {
            $(this).remove();
        })
    })
</script>

