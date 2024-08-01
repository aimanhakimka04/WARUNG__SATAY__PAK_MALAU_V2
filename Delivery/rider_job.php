<?php include '../admin/header.php';
$rider_id = $_SESSION['login_id'];
include '../admin/db_connect.php';

// Fetch current delivery status for the rider
$current_delivery_status_query = $conn->query("
    SELECT COUNT(*) as pending_deliveries 
    FROM rider 
    WHERE staff_id = $rider_id 
    AND status_delivery = 1
");

$current_delivery_status = $current_delivery_status_query->fetch_assoc();
$has_pending_delivery = $current_delivery_status['pending_deliveries'] > 0 ? true : false;
?>

<script>
    var hasPendingDelivery = <?php echo json_encode($has_pending_delivery); ?>;
</script>

<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Delivery Order Customer</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="#">Delivery Order Customer</a></li>
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
                                include '../admin/db_connect.php';
                                $qry = $conn->query("
                                 SELECT o.*, r.status_delivery FROM rider r JOIN orders o ON r.order_id = o.id WHERE r.staff_id = $rider_id
                            ");
                                while ($row = $qry->fetch_assoc()) :
                                ?> <div class="form1" style="display: none;" id="form1">
                                        <input type="text" id="order-id" name="orderid" value="<?php echo htmlspecialchars($order_id); ?>" readonly>

                                        <tr>
                                            <td><?php echo $i++ ?></td>
                                            <td><?php echo $row['id'] ?></td>
                                            <td><button class="btn btn-sm btn-primary view_order btn-lg" data-id="<?php echo $row['id'] ?>">Order</button></td>
                                            <td><button class="btn btn-sm btn-primary view_customer btn-lg" data-id="<?php echo $row['id'] ?>">Customer Detail</button></td>
                                            <?php if ($row['status_delivery'] == 0) : ?>
                                                <td class="text-center"><span class="badge badge-secondary btn-lg">Pending Delivery</span></td>
                                                <td> <button class="ac_button" data-id="<?php echo $row['id'] ?>" onclick="confirmOrder(<?php echo $row['id'] ?>)">Deliver This Order</button></td>
                                            <?php elseif ($row['status_delivery'] == 1) : ?>
                                                <td class="text-center"><span class="badge badge-primary btn-lg">On Delivery</span></td>
                                                <td class="text-center"><span class="badge badge-primary btn-lg">On Delivery</span></td>
                                            <?php elseif ($row['status_delivery'] == 2) : ?>
                                                <td class="text-center"><span class="badge badge-success btn-lg">Order Delivered</span></td>
                                                <td> <button class="viewproofbtn" data-id="<?php echo $row['id'] ?>">View Order Proof</button> </td>
                                            <?php endif; ?>
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
<script>
    
    $('.view_order').click(function() {
        uni_modal('Order', '../admin/view_order.php?id=' + $(this).attr('data-id'))
    })

    $('.view_customer').click(function() {
        uni_modal('', '../admin/customerorderdetail.php?id=' + $(this).attr('data-id'))
    })
    $('.rider_deliverypick').click(function() {
        uni_modal('', '../admin/customer_detail.php?id=' + $(this).attr('data-id'))
    })
    $('.viewproofbtn').click(function() {
        uni_modal('', 'proofview.php?id=' + $(this).attr('data-id'))
    })


    function readypickup_order(order_id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=readypickup_order',
            method: 'POST',
            data: {
                id: order_id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Order Ready for Pickup.")
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


    function confirmOrder(order_id) {
    const orderId = order_id;
    const staffId = "<?php echo $rider_id; ?>";

    if (hasPendingDelivery) {
        alert("You already have a pending delivery order.");
        return;
    }

    if (orderId && staffId) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=deliveryrider',
            method: 'POST',
            data: {
                orderid: orderId,
                staffid: staffId
            },
            error: function(err) {
                console.error(err);
                alert("An error occurred. Please try again.");
            },
            success: function(resp) {
                const response = JSON.parse(resp);
                if (response.status == 1) {
                    alert_toast(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    alert(response.message);
                }
            }
        });
    } else {
        alert('Please fill in all fields.');
    }
}


    // Ensure start_load function exists
</script>