<?php
include 'db_connect.php';
include('header.php');
include('footeradmin.php');
?>



<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Customer List</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="#">Customer List</a></li>
                    <li><a href="#">Table</a></li>
                    <li class="active">Data table</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content mt-3">
    <div class="animated fadeIn">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Admin</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                            <thead>
                                <tr>

                                <th class="text-center1">#</th>
                                <th class="text-center2">Name</th>
                                <th class="text-center3">Username</th>
                                <th class="text-center4">Gender</th>
                                <th class="text-center3">Email</th>
                                <th class="text-center5">Action</th>
                                <th class="text-center5">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $admins = $conn->query("SELECT * FROM users WHERE type = 1 ORDER BY name ASC");
                            $i = 1;
                            while ($row = $admins->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $row['name'] ?></td>
                                    <td><?php echo $row['username'] ?></td>
                                    <td><?php echo ($row['gender'] == 1) ? 'Male' : 'Female'; ?></td>
                                    <td><?php echo $row['email'] ?></td>
                                    <td>
                                        <center>
                                            <button class="btn btn-primary edit_user" data-id='<?php echo $row['id'] ?>'>Edit</button>
                                            <button class="btn btn-danger delete_user" data-id='<?php echo $row['id'] ?>'>Delete</button>
                                        </center>
                                    </td>
                                    <td>Admin</td>
                                </tr>
                            <?php 
                            endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div><!-- .animated -->
</div><!-- .content -->



<div class="content mt-3">
    <div class="animated fadeIn">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Staff</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                            <thead>
                                <tr>

                                <th class="text-center1">#</th>
                                <th class="text-center2">Name</th>
                                <th class="text-center3">Username</th>
                                <th class="text-center4">Gender</th>
                                <th class="text-center3">Email</th>
                                <th class="text-center5">Action</th>
                                <th class="text-center5">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $admins = $conn->query("SELECT * FROM users WHERE type = 2 ORDER BY name ASC");
                            $i = 1;
                            while ($row = $admins->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $row['name'] ?></td>
                                    <td><?php echo $row['username'] ?></td>
                                    <td><?php echo ($row['gender'] == 1) ? 'Male' : 'Female'; ?></td>
                                    <td><?php echo $row['email'] ?></td>
                                    <td>
                                        <center>
                                            <button class="btn btn-primary edit_user" data-id='<?php echo $row['id'] ?>'>Edit</button>
                                            <button class="btn btn-danger delete_user" data-id='<?php echo $row['id'] ?>'>Delete</button>
                                        </center>
                                    </td>
                                    <td>Staff</td>
                                </tr>
                            <?php 
                            endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div><!-- .animated -->
</div><!-- .content -->





<div class="addadmin">
    <button class="btn btn-primary" id="new_user"><i class="fa fa-plus"></i> Add Admin</button>
</div>
<script>
    $('#new_user').click(function () {
        uni_modal('New User', 'manage_user.php')
    })

    $('.edit_user').click(function () {
        uni_modal('Edit User', 'manage_user.php?id=' + $(this).attr('data-id'))
    })

    $('.delete_user').click(function () {
        var user_id = $(this).attr('data-id');
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: 'delete_user.php',
                method: 'POST',
                data: { id: user_id },
                success: function (response) {
                    if (response == 'success') {
                        alert('User deleted successfully');
                        // You can reload the page or update the user list as needed-
                        location.reload();
                    } else {
                        alert('Failed to delete user');
                    }
                }
            });
        }
    });
</script>

<style>
    body {
        background-color: #f8f9fa;
    }

    .container-fluid {
        margin-top: 20px;
    }

    .text-center1 {
        text-align: center;
        width: 10%;
    }

    .text-center2 {
        text-align: center;
        width: 20%;
    }

    .text-center3 {
        text-align: center;
        width: 15%;
    }

    .text-center4 {
        text-align: center;
        width: 15%;
    }

    .text-center5 {
        text-align: center;
        width: 15%;
    }

    .card {
        background-color: #ffffff;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th, td {
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #343a40;
        color: #ffffff;
    }

    tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .btn-group {
        margin-top: 5px;
    }
</style>
