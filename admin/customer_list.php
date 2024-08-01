<?php include 'header.php';
include 'db_connect.php';
$users = $conn->query("SELECT * FROM user_info ORDER BY user_id ASC");
$i = 1;
?>

<style>
    

</style>

<? include('footeradmin.php');?>
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
                        <strong class="card-title">Data Table</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                            <thead>
                                <tr>

                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Registered Date</th>
                                    <th>Last Login</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                while ($row = $users->fetch_assoc()) :
                                    // Set button class and text based on status_hide value
                                    $button_class = $row['hide_status'] == 1 ? 'btn-danger' : 'btn-success';
                                    $button_text = $row['hide_status'] == 1 ? 'Hide' : 'Show';
                                ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $row['user_id'] ?></td>
                                        <td><?php echo $row['first_name'] ?> <?php echo $row['last_name'] ?></td>
                                        <td><?php echo $row['email'] ?></td>
                                        <td><?php echo $row['created_at'] ?></td>
                                        <td><?php echo $row['last_login'] ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary view_customer" data-id="<?php echo $row['user_id'] ?>">View</button>
                                            <button class="btn btn-sm btn-info edit_customer" data-id="<?php echo $row['user_id'] ?>">Edit</button>
                                            <button class="btn btn-sm <?php echo $button_class; ?> toggle_customer" data-id="<?php echo $row['user_id']; ?>">
                                                <?php echo $button_text; ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div><!-- .animated -->
</div><!-- .content -->


<script>
    $('.view_customer').click(function() {
        uni_modal('Customer Detail', 'customer_detail.php?id=' + $(this).attr('data-id'))
    })

    $('.edit_customer').click(function() {
        uni_modal('Edit Customer', 'manage_customer.php?id=' + $(this).attr('data-id'))
    })
     // Function to handle hide confirmation
     $('.toggle_customer').click(function() {
        var button = $(this);
        var customer_id = button.data('id');
        var current_status = button.hasClass('btn-danger') ? 1 : 0;
        var new_status = current_status == 1 ? 0 : 1;
        var action = new_status == 1 ? 'hide' : 'show';

        if (confirm('Are you sure you want to ' + action + ' this customer?')) {
            $.ajax({
                url: 'hide_customer.php',
                type: 'POST',
                data: { id: customer_id, status: new_status },
                success: function(response) {
                    if (response == 1) {
                        if (new_status == 1) {
                            button.removeClass('btn-success').addClass('btn-danger');
                            button.text('Hide');
                        } else {
                            button.removeClass('btn-danger').addClass('btn-success');
                            button.text('Show');
                        }
                        alert('Customer status updated successfully.');
                    } else {
                        alert('Failed to update customer status.');
                    }
                }
            });
        }
    });


    function confirm_order(order_id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=confirm_order',
            method: 'POST',
            data: {
                id: order_id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Order confirmed.")
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }

    function reject_order(order_id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=reject_order',
            method: 'POST',
            data: {
                id: order_id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Order rejected.")
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }

    function cancel_order(order_id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=cancel_order',
            method: 'POST',
            data: {
                id: order_id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Order Cancel.")
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>









