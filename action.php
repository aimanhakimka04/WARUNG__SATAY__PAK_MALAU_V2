<?php
session_start(); // Start the session at the beginning

// action.php



if (isset($_POST['action'])) {
    require 'database/PrivateChat.php';

    // Initialize PrivateChat object
    $private_chat_object = new PrivateChat();

    switch ($_POST['action']) {
        case 'fetch_chat':
            // Validate and sanitize input
            $to_user_id = isset($_POST["to_user_id"]) ? intval($_POST["to_user_id"]) : 0;
            $from_user_id = isset($_POST["from_user_id"]) ? intval($_POST["from_user_id"]) : 0;

            if ($to_user_id > 0 && $from_user_id > 0) {
                $private_chat_object->setFromUserId($from_user_id);
                $private_chat_object->setToUserId($to_user_id);
                echo json_encode($private_chat_object->get_all_chat_data());
            } else {
                echo json_encode(['error' => 'Invalid user IDs.']);
            }
            break;

        case 'send_message':
            // Validate and sanitize input
            $to_user_id = isset($_POST["to_user_id"]) ? intval($_POST["to_user_id"]) : 0;
            $from_user_id = isset($_POST["from_user_id"]) ? intval($_POST["from_user_id"]) : 0;
            $message = isset($_POST["message"]) ? trim($_POST["message"]) : '';
            $role = isset($_POST["role"]) ? trim($_POST["role"]) : '';

            if ($to_user_id > 0 && $from_user_id > 0 && !empty($message) && in_array($role, ['admin', 'user'])) {
                $private_chat_object->setFromUserId($from_user_id);
                $private_chat_object->setToUserId($to_user_id);
                $private_chat_object->setChatMessage($message);
                $private_chat_object->setTimestamp(date('Y-m-d H:i:s')); // Current timestamp
                $private_chat_object->setStatus(1); // Assuming 1 means 'sent'
                $private_chat_object->setRole($role);

                $save_status = $private_chat_object->save_chat();

                if ($save_status) {
                    echo json_encode(['success' => 'Message sent successfully.']);
                } else {
                    echo json_encode(['error' => 'Failed to send message.']);
                }
            } else {
                echo json_encode(['error' => 'Invalid input parameters.']);
            }
            break;

        case 'delete_conversation':
            // Validate and sanitize input
            $to_user_id = $_POST['to_user_id'];
            $from_user_id = $_POST['from_user_id'];

            // Prepare and execute query to delete conversation
            $query = "DELETE FROM chat_messages WHERE (from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiii", $from_user_id, $to_user_id, $to_user_id, $from_user_id);
            $query1 = "DELETE FROM chatsecurity WHERE userid = ?";
            $stmt1 = $conn->prepare($query);
            $stmt1->bind_param("i", $to_user_id);
            if ($stmt->execute() && $stmt1->execute()) {
                echo json_encode(['success' => 'Conversation deleted successfully.']);
            } else {
                echo json_encode(['error' => 'Failed to delete conversation.']);
            }

            break;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
} else {
    echo json_encode(['error' => 'No action specified.']);
}
