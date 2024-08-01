<?php
include 'admin/db_connect.php';

if (isset($_GET['id'])) {
    $selectedDrink = $_GET['id'];
    
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT price FROM product_list WHERE id = ?");
    $stmt->bind_param('i', $selectedDrink);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $price = $row['price'];
            
            echo json_encode(['success' => true, 'price' => $price]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Drink not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database query failed']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No drink ID provided']);
}

$conn->close();
?>
