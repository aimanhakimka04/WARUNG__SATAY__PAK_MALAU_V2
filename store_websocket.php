<?php
session_start();
require __DIR__ . "/database/ChatRooms.php"; // Make sure the path is correct// Ensure this file correctly includes the ChatRooms class

header('Content-Type: application/json');
$chat_object = new ChatRooms(); // Create an instance of ChatRooms 
 if ($chat_object->checkConnection()) {
        echo json_encode(['status' => 'success', 'message' => 'Database connection is successful.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to connect to the database.']);
    }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    
    
    $userid = isset($_POST['userid']) ? intval($_POST['userid']) : 0;
    $link_webSocket = isset($_POST['link_webSocket']) ? $_POST['link_webSocket'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $user_token = isset($_POST['user_token']) ? $_POST['user_token'] : '';

    // Check if parameters are valid
    if ($userid > 0 && !empty($link_webSocket)) {
        $chat_object = new ChatRooms(); // Create an instance of ChatRooms

        // Set the necessary properties for saving WebSocket link
        $chat_object->setUserId($userid);
        $chat_object->setLink($link_webSocket);
        $chat_object->setStaffId(1); // Assuming 1 for now or set dynamically
        $chat_object->setStatus(0);  // Assuming 0 for "PENDING"
        $chat_object->setRole($role);
        $chat_object->setUserToken($user_token);

        
        // Call the method to save the WebSocket link
        $result = $chat_object->save_websocket_link();

        // Return success or failure response
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to store WebSocket link.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid parameters.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
