<!DOCTYPE html>
<html lang="en">
<head>
    
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width:600px; height:400px;">
    <canvas id="loginChart" width="600" height="400"></canvas>
    </div>

    <script>
        // PHP to retrieve data from the server
        <?php
            // Include database connection
            include 'db_connect.php';

            // Query to select last_login for this month
            $query = "SELECT last_login FROM user_info WHERE MONTH(last_login) = MONTH(CURRENT_DATE())";

            $result = mysqli_query($conn, $query);

            // Array to store login counts for each day of the month
            $login_counts = array_fill(1, date('t'), 0);

            // Calculate login counts for each day
            while ($row = mysqli_fetch_assoc($result)) {
                $login_day = date('j', strtotime($row['last_login']));
                $login_counts[$login_day]++;
            }

            // Prepare data for Chart.js
            $labels = array_keys($login_counts);
            $data = array_values($login_counts);

            // Close MySQL connection
            mysqli_close($conn);
        ?>

        // JavaScript to render the bar chart
        var ctx = document.getElementById('loginChart').getContext('2d');
        var loginChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Login User',
                    data: <?php echo json_encode($data); ?>,
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
                            stepSize: 10, // Set the step size
                            callback: function(value, index, values) {
                                return value; // Ensure ticks are displayed as integers
                            }
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>
