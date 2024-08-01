<link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
<link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
<link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
<link rel="stylesheet" href="vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">

<link rel="stylesheet" href="assets/css/style.css">

<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>



<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="#">Dashboard</a></li>
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
                                    <th>Order ID</th>
                                    <th>Order Detail</th>
                                    <th>Customer Detail</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                include 'db_connect.php';
                                $qry = $conn->query("SELECT * FROM orders ");
                                while ($row = $qry->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $row['id'] ?></td>
                                        <td><button class="btn btn-sm btn-primary view_order" data-id="<?php echo $row['id'] ?>">Order</button></td>
                                        <td><button class="btn btn-sm btn-primary view_customer" data-id="<?php echo $row['id'] ?>">Customer Detail</button></td>
                                        <?php if ($row['status'] == 1) : ?>
                                            <td class="text-center"><span class="badge badge-success">Confirmed</span></td>
                                        <?php elseif ($row['status'] == 2) : ?>
                                            <td class="text-center"><span class="badge badge-cancel">Cancel</span></td>
                                        <?php else : ?>
                                            <td class="text-center"><span class="badge badge-secondary">For Verification</span></td>
                                        <?php endif; ?>

                                        <td>
                                            <button class="ac_button" id="confirm_<?php echo $row['id'] ?>" type="button" onclick="confirm_order('<?php echo $row['id'] ?>')">Confirm</button>
                                            <button class="ac_button" id="reject_<?php echo $row['id'] ?>" type="button" onclick="reject_order('<?php echo $row['id'] ?>')">Reject</button>
                                            <button class="ac_button" id="delete_<?php echo $row['id'] ?>" type="button" onclick="cancel_order('<?php echo $row['id'] ?>')">Cancel</button>
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


</div><!-- /#right-panel -->

<!-- Right Panel -->


<script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/popper.js/dist/umd/popper.min.js"></script>
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="vendors/jszip/dist/jszip.min.js"></script>
<script src="vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="vendors/pdfmake/build/vfs_fonts.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="assets/js/init-scripts/data-table/datatables-init.js"></script>



<script>
    $('.view_order').click(function() {
        uni_modal('Order', 'view_order.php?id=' + $(this).attr('data-id'))
    })

    $('.view_customer').click(function() {
        uni_modal('Customer Detail', 'customer_detail.php?id=' + $(this).attr('data-id'))
    })

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