<?php require_once("./db_connect.php"); ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .dashboard-container {
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }
        .card .card-body {
            display: flex;
            align-items: center;
        }
        .card .card-body .details {
            margin-left: 20px;
        }
        .card .card-body .details .num {
            font-size: 24px;
            font-weight: bold;
        }
        .card .card-body .details .name {
            font-size: 14px;
            font-weight: bold;
            color: #6c757d;
        }
        .chart-container {
            margin-top: 50px;
        }
        .chart-container canvas {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .dateorder {
            display: flex;
            justify-content: center;
            background-color: #e2e2e2;
            width: 160px;
            border-radius: 10px;
            margin: 0 auto 20px auto;
        }
        .btndateo {
            margin: 2px;
            background-color: #e2e2e2;
            border: 1px solid #e2e2e2;
            border-radius: 10px;
            width: 50px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: black;
        }
        .btndateo:hover {
            background-color: #ccc;
        }
        .chart-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php 
    // Include your database connection script
    require_once("./db_connect.php"); 

    // Fetch the count of active menu items
    $menu_a = $conn->query("SELECT * FROM `product_list` where `status` = 1")->num_rows;

    // Fetch the count of inactive menu items
    $menu_i = $conn->query("SELECT * FROM `product_list` where `status` = 0")->num_rows;

    // Fetch the count of orders for verification
    $status_0 = $conn->query("SELECT COUNT(*) as count FROM `orders` where `status` = 0")->fetch_assoc()['count'];
    $status_1 = $conn->query("SELECT COUNT(*) as count FROM `orders` where `status` = 1")->fetch_assoc()['count'];
    $status_2 = $conn->query("SELECT COUNT(*) as count FROM `orders` where `status` = 2")->fetch_assoc()['count'];
    $total_orders = $status_0 + $status_1 + $status_2;

    // Count the total number of users
    $total_user = $conn->query("SELECT COUNT(*) as count FROM `user_info`")->fetch_assoc()['count'];

    ?>

    <div class="container dashboard-container">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQRr0YlatAy-hrNCQjzZ7fqDzNiXt7HGmzVaA&usqp=CAU" alt="User Image">
                        <div class="details">
                            <p class="num"><?= number_format($menu_a) ?></p>
                            <p class="name">Total Active Menu</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQRr0YlatAy-hrNCQjzZ7fqDzNiXt7HGmzVaA&usqp=CAU" alt="User Image">
                        <div class="details">
                            <p class="num"><?= number_format($menu_i) ?></p>
                            <p class="name">Total Inactive Menu</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQRr0YlatAy-hrNCQjzZ7fqDzNiXt7HGmzVaA&usqp=CAU" alt="User Image">
                        <div class="details">
                            <p class="num"><?= number_format($total_orders) ?></p>
                            <p class="name">Total Orders</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQRr0YlatAy-hrNCQjzZ7fqDzNiXt7HGmzVaA&usqp=CAU" alt="User Image">
                        <div class="details">
                            <p class="num"><?= number_format($total_user) ?></p>
                            <p class="name">Total Users</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // Function to get data for the current week
          // Function to get data for the current week
        function getDataForCurrentWeek($conn) {
            $currentWeek = date('W');
            $currentYear = date('Y');
            $result = $conn->query("SELECT COUNT(*) as count, status FROM orders WHERE WEEKOFYEAR(date) = $currentWeek AND YEAR(date) = $currentYear GROUP BY status");
            return processData($result);
        }

        // Function to get data for the current month
        function getDataForCurrentMonth($conn) {
            $currentMonth = date('m');
            $currentYear = date('Y');
            $result = $conn->query("SELECT COUNT(*) as count, status FROM orders WHERE MONTH(date) = $currentMonth AND YEAR(date) = $currentYear GROUP BY status");
            return processData($result);
        }

        // Function to get data for the current day
        function getDataForCurrentDay($conn) {
            $currentDate = date('Y-m-d');
            $result = $conn->query("SELECT COUNT(*) as count, status FROM orders WHERE DATE(date) = '$currentDate' GROUP BY status");
            return processData($result);
        }

        // Function to get data for payment methods
        function getDataForPaymentMethods($conn) {
            $result = $conn->query("SELECT COUNT(*) as count, payment_method FROM orders GROUP BY payment_method");
            return processPaymentData($result);
        }


        // Function to process data fetched from the database
        function processData($result) {
            $orderData = array(
                'Order Confirmed' => 0,
                'Order Cancel' => 0,
                'Order On Delivery' => 0,
                'Order Delivered' => 0,
                'Order Completed' => 0,
                'Order For Verification' => 0
            );
        
            while ($row = $result->fetch_assoc()) {
                switch ($row['status']) {
                    case 1:
                        $orderData['Order Confirmed'] += $row['count'];
                        break;
                    case 2:
                        $orderData['Order Cancel'] += $row['count'];
                        break;
                    case 3:
                        $orderData['Order On Delivery'] += $row['count'];
                        break;
                    case 4:
                        $orderData['Order Delivered'] += $row['count'];
                        break;
                    case 5:
                        $orderData['Order Completed'] += $row['count'];
                        break;
                    default:
                        $orderData['Order For Verification'] += $row['count'];
                        break;
                }
            }
            return $orderData;
        }
        




        // Check which button is clicked and get data accordingly
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'week':
                    $orderData = getDataForCurrentWeek($conn);
                    break;
                case 'month':
                    $orderData = getDataForCurrentMonth($conn);
                    break;
                case 'day':
                    $orderData = getDataForCurrentDay($conn);
                    break;
                default:
                    $orderData = getDataForCurrentMonth($conn);
                    break;
            }
        } else {
            $orderData = getDataForCurrentMonth($conn);
        }
        ?>

        <div class="chart-container">
            <div class="chart-title">Order View</div>
            <div class="dateorder">
                <a class="btndateo" href="?action=month">Month</a>
                <a class="btndateo" href="?action=week">Week</a>
                <a class="btndateo" href="?action=day">Day</a>
            </div>
            <div id="pieChart" style="width: 600px; height: 400px; margin: 0 auto;"></div>
        </div>

        <!---div class="chart-container">
            <div class="chart-title">User Logins This Month</div>
            <canvas id="loginChart" width="600" height="400" style="margin: 0 auto;"></canvas>
        </div--->

     

    </div>

    <!-- Include ECharts and Chart.js libraries -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize ECharts instance for the pie chart
        var pieChart = echarts.init(document.getElementById('pieChart'));
        var pieOption = {
            legend: {
                orient: 'vertical',
                left: '30px',
                top: '15px',
                data: ['Order Confirmed', 'Order Cancel', 'Order On Delivery', 'Order Delivered', 'Order Completed', 'Order For Verification']
            },
            series: [{
                name: 'Pie Chart',
                type: 'pie',
                data: [
                    { value: <?= $orderData['Order Confirmed']; ?>, name: 'Order Confirmed' },
                    { value: <?= $orderData['Order Cancel']; ?>, name: 'Order Cancel' },
                    { value: <?= $orderData['Order On Delivery']; ?>, name: 'Order On Delivery' },
                    { value: <?= $orderData['Order Delivered']; ?>, name: 'Order Delivered' },
                    { value: <?= $orderData['Order Completed']; ?>, name: 'Order Completed' },
                    { value: <?= $orderData['Order For Verification']; ?>, name: 'Order For Verification' }
                ],
                radius: ['30%', '50%'],
                label: {
                    show: true,
                    formatter: '{b}: {c} '
                }
            }]
        };

        pieChart.setOption(pieOption);

      

        // PHP to retrieve data from the server
        <?php
            $query = "SELECT last_login FROM user_info WHERE MONTH(last_login) = MONTH(CURRENT_DATE())";
            $result = mysqli_query($conn, $query);
            $login_counts = array_fill(1, date('t'), 0);
            while ($row = mysqli_fetch_assoc($result)) {
                $login_day = date('j', strtotime($row['last_login']));
                $login_counts[$login_day]++;
            }
            $labels = array_keys($login_counts);
            $data = array_values($login_counts);
            mysqli_close($conn);
        ?>
        var ctx = document.getElementById('loginChart').getContext('2d');
        var loginChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels); ?>,
                datasets: [{
                    label: 'Login User',
                    data: <?= json_encode($data); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 10,
                            callback: function(value, index, values) {
                                return value;
                            }
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>


