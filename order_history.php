<?php
include 'admin/db_connect.php';

$chk = $conn->query("SELECT * FROM cart where user_id = 1 ")->num_rows;
?>
 <div style="margin-top:90px"></div>

 <!-- Scroll buttons -->
    <button class="scroll-btn scrolltop" onclick="scrollToTop()">Top</button>
    <button class="scroll-btn scrollcenter" onclick="scrollToCenter()">Center</button>
    <button class="scroll-btn scrollbottom" onclick="scrollToBottom()">Bottom</button>


<div class="content-wrapper">
    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                   

                    <div class="card">
                        <div class="card-header" style="text-align: center;">
                            <strong class="card-title">Order List</strong>
                        </div>
                        <div class="card-body">
                            <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date Time Order</th>
                                        <th>Date Time Collect</th>
                                        <th>Status</th>
                                        <th>Product</th>
                                        <th>Image</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Collect Method</th>
                                        <th>Payment Method</th>
                                        <th>Total Quantity</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = "SELECT * FROM orders WHERE email = '{$_SESSION['login_email']}'";
                                    $result = $conn->query($data);

                                    $orders = [];
                                    $i = 1;

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $order_id = $row["id"];
                                            if (!isset($orders[$order_id])) {
                                                $orders[$order_id] = [
                                                    'order' => $row,
                                                    'order_list' => []
                                                ];
                                            }

                                            $order_list_query = "SELECT ol.*, p.img_path, p.name, p.price 
                                                                 FROM order_list ol 
                                                                 INNER JOIN product_list p ON p.id = ol.product_id 
                                                                 WHERE ol.order_id = $order_id";
                                            $order_list_result = $conn->query($order_list_query);

                                            while ($order_list_row = $order_list_result->fetch_assoc()) {
                                                if (!isset($orders[$order_id]['order_list'][$order_list_row['product_id']])) {
                                                    $orders[$order_id]['order_list'][$order_list_row['product_id']] = [
                                                        'name' => $order_list_row['name'],
                                                        'img_path' => $order_list_row['img_path'],
                                                        'qty' => 0,
                                                        'price' => $order_list_row['price']
                                                    ];
                                                }
                                                $orders[$order_id]['order_list'][$order_list_row['product_id']]['qty'] += $order_list_row['qty'];
                                            }
                                        }
                                    }

                                    foreach ($orders as $order_id => $order_data) {
                                        $rowspan = count($order_data['order_list']);
                                        $order = $order_data['order'];

                                        $status = $order["status"];
                                        $status_text = "Unknown";
                                        $order_track = '';

                                        if ($status == 0) {
                                            $status_text = "Waiting for order confirmation by seller";
                                            $order_track = '<a href="index.php?page=order_track&id=' . $order["id"] . '" class="btn btn-primary">Order Details</a>';
                                        } elseif ($status == 1) {
                                            $status_text = "Order confirmed by seller - Preparing your order";
                                            $order_track = '<a href="index.php?page=order_track&id=' . $order["id"] . '" class="btn btn-primary">Order Details</a>';
                                        } elseif ($status == 2) {
                                            $status_text = "Order canceled";
                                        } elseif ($status == 3) {
                                            $status_text = "Order In Delivery";
                                            $order_track = '<a href="index.php?page=order_track&id=' . $order["id"] . '" class="btn btn-primary">Order Details</a>';
                                        } elseif ($status == 4) {
                                            $status_text = "Order Delivered";
                                            $order_track = '<a href="index.php?page=order_track&id=' . $order["id"] . '" class="btn btn-primary">Order Details</a>';
                                        } elseif ($status == 5) {
                                            $status_text = "Order Completed";
                                            $order_track = '<a href="index.php?page=order_track&id=' . $order["id"] . '" class="btn btn-primary">Order Details</a>';
                                        }

                                        $total_quantity = 0;
                                        $total_price = 0;
                                        $delivery_fee = 0;
                                        $total_fee = $order["total_fee"];

                                        foreach ($order_data['order_list'] as $order_list_row) {
                                            $total_quantity += $order_list_row["qty"];
                                            $total_price += $order_list_row["price"] * $order_list_row["qty"];
                                        }

                                        if ($order["collect_method"] == "delivery") {
                                            $delivery_fee = $order["delivery_fee"];
                                        }

                                        $first = true;
                                        foreach ($order_data['order_list'] as $product_id => $order_list_row) {
                                            echo '<tr>';
                                            if ($first) {
                                                echo '<td rowspan="' . $rowspan . '">' . $i++ . '</td>';
                                                echo '<td rowspan="' . $rowspan . '">' . $order["date"] . '</td>';
                                                echo '<td rowspan="' . $rowspan . '">' . $order["collect_time"] . '</td>';
                                                echo '<td rowspan="' . $rowspan . '" class="center-cell">' . $status_text . '<br>' . $order_track . '</td>';
                                                
                                                echo '<td>' . $order_list_row["name"] . '</td>';
                                                echo '<td><img src="assets/img/' . $order_list_row['img_path'] . '" alt="" style="width:50px;height:50px;"></td>';
                                                echo '<td>' . $order_list_row["qty"] . '</td>';
                                                echo '<td>RM' . number_format($order_list_row["price"], 2) . '</td>';

                                                echo '<td rowspan="' . $rowspan . '">';

                                                if ($order["collect_method"] == "delivery") {
                                                    echo 'Delivery<br> RM ' . number_format($delivery_fee, 2);
                                                } else {
                                                    echo $order["collect_method"];
                                                }

                                                echo '</td>';
                                                echo '<td rowspan="' . $rowspan . '">' . $order["payment_method"] . '</td>';
                                                echo '<td rowspan="' . $rowspan . '">' . $total_quantity . '</td>';
                                                echo '<td rowspan="' . $rowspan . '">RM' . number_format($total_fee, 2) . '</td>';
                                                $first = false;
                                            } else {
                                                echo '<td>' . $order_list_row["name"] . '</td>';
                                                echo '<td><img src="assets/img/' . $order_list_row['img_path'] . '" alt="" style="width:50px;height:50px;"></td>';
                                                echo '<td>' . $order_list_row["qty"] . '</td>';
                                                echo '<td>RM' . number_format($order_list_row["price"], 2) . '</td>';
                                            }
                                            echo '</tr>';
                                        }
                                    }

                                    if (empty($orders
                                    )) {
                                        echo '<tr>';
                                        echo '<td colspan="12">No orders found for this email.</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                    </tbody>
                                    </table>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div><!-- .animated -->
                                    </div><!-- .content -->
                                    </div><!-- .content-wrapper -->
                                    

                                    <style>
        /* Table header style */
        #data-table thead th {
            background-color: #007bff; /* Blue color */
            color: #ffffff; /* White text */
        }

        /* Alternating row colors for better readability */
        #data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa; /* Light grey color */
        }

        /* Hover effect for better interaction */
        #data-table tbody tr:hover {
            background-color: #cce5ff; /* Light blue color */
        }

        /* Style for scroll buttons */
        .scroll-btn {
            position: fixed;
           
            top:50%;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 9999;
        }

        /* Adjust position for left-hand side buttons */
        .scrolltop {
            left: 20px;   
            width: 100px;     
        }
        .scrollcenter {
            margin-top: 60px;
            left: 20px;
            width: 100px;     

        }
        .scrollbottom {
            margin-top: 120px;
            left: 20px;
            width: 100px;     

        }
        
      
    </style>
 <script>
        // Function to scroll to the top of the page
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Function to scroll to the center of the page
        function scrollToCenter() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.body.scrollHeight;
        const center = (documentHeight - windowHeight) / 2;
        window.scrollTo({ top: center, behavior: 'smooth' });
    }
        // Function to scroll to the bottom of the page
        function scrollToBottom() {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    </script>