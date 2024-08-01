<?php 
    include 'db_connect.php';
    $order_id = $_GET['id'];
    $qry = $conn->query("SELECT * FROM orders WHERE id = $order_id");
    $row = $qry->fetch_assoc();
?>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .customer-detail-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 50px auto;
        }
        .customer-detail-container h2 {
            margin-bottom: 20px;
            font-size: 1.75rem;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-secondary {
            margin-top: 20px;
        }
        .modal-footer {
            display: none;
        }
    </style>
</head>
<body>
    <div class="customer-detail-container">
        <h2 class="text-center"><i class="fas fa-user"></i> Customer Detail</h2>
        <table class="table table-striped table-hover">
            <tr>
                <th><i class="fas fa-id-badge"></i> Customer ID</th>
                <td><?php echo $row['id'] ?></td>
            </tr>
            <tr>
                <th><i class="fas fa-user"></i> Customer Name</th>
                <td><?php echo $row['name'] ?></td>
            </tr>
            <tr>
                <th><i class="fas fa-map-marker-alt"></i> Customer Address</th>
                <td><?php echo $row['address'] ?></td>
            </tr>
            <tr>
                <th><i class="fas fa-mobile-alt"></i> Customer Mobile</th>
                <td><?php echo $row['mobile'] ?></td>
            </tr>
            <tr>
                <th><i class="fas fa-envelope"></i> Customer Email</th>
                <td><?php echo $row['email'] ?></td>
            </tr>
            <tr>
                <th><i class="fas fa-calendar-alt"></i> Order Date</th>
                <td><?php echo $row['date'] ?></td>
            </tr>
            <tr>
                <th><i class="fas fa-calendar-alt"></i> Collect Date</th>
                <td><?php echo $row['collect_time'] ?></td>
            </tr>

            <tr>
                <th><i class="fas fa-money-bill-wave"></i> Total Amount</th>
                <td><?php echo $row['total_fee'] ?></td>
            </tr>

          
        </table>
        <div class="text-center">
            <!--<button class="btn btn-primary" id="confirm" type="button" onclick="confirm_order()">Confirm</button>-->
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</body>
</html>
