<?php
// admin_queue.php
session_start();
require 'db_connection.php';

$staff_email = $_SESSION['login_email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_email = $_POST['user_email'];

    // Fetch the user's WebSocket resourceId from the database (or from the WebSocket server)
    $stmt = $conn->prepare("SELECT resourceId FROM users WHERE email = ?");
    $stmt->bind_param('s', $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo json_encode(['response' => 'You are now connected with the user.', 'resourceId' => $user['resourceId']]);
    } else {
        echo json_encode(['response' => 'User not found.']);
    }
}
?>
<html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<body>
    <h1>Admin Queue</h1>
    <table border="1">
        <thead>
            <tr>
                <th>User Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM support_queue WHERE staff_assign = FALSE");

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['sender_email']}</td>";
                echo "<td><button onclick=\"pickUser('{$row['sender_email']}')\">Pick</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    
    <script>
        function pickUser(userEmail) {
            $.post('admin_queue.php', { user_email: userEmail }, function(data) {
                alert(data.response);
                if (data.resourceId) {
                    setUserId(data.resourceId); // Set the userId in the chat interface
                }
            }, 'json');
        }

        function setUserId(resourceId) {
            // Implement this function in the admin chat interface to set the user ID
            // This function should be used in the chat interface where admin sends messages
            console.log("User ID set to:", resourceId);
        }
    </script>
</body>
</html>
