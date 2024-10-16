<?php

class PrivateChat
{
    private $chat_message_id;
    private $to_user_id;
    private $from_user_id;
    private $chat_message;
    private $timestamp;
    private $status;
    protected $connect;
    private $role;
    private $sender_type;

    public function __construct()
    {
        include __DIR__ . "/../admin/db_connect.php";
        $this->connect = $conn;
    }

    // Setters and Getters for class properties
    function setChatMessageId($chat_message_id)
    {
        $this->chat_message_id = $chat_message_id;
    }
    function getChatMessageId()
    {
        return $this->chat_message_id;
    }
    function setToUserId($to_user_id)
    {
        $this->to_user_id = $to_user_id;
    }
    function getToUserId()
    {
        return $this->to_user_id;
    }
    function setFromUserId($from_user_id)
    {
        $this->from_user_id = $from_user_id;
    }
    function getFromUserId()
    {
        return $this->from_user_id;
    }
    function setChatMessage($chat_message)
    {
        $this->chat_message = $chat_message;
    }
    function getChatMessage()
    {
        return $this->chat_message;
    }
    function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
    function getTimestamp()
    {
        return $this->timestamp;
    }
    function setStatus($status)
    {
        $this->status = $status;
    }
    function getStatus()
    {
        return $this->status;
    }
    function setRole($role)
    {
        $this->role = $role;
    }
    function getRole()
    {
        return $this->role;
    }

    // Fetch all chat data between two users
    function get_all_chat_data()
    {
        $sql = "
            SELECT 
                IF(cm.sender_type = 0, u.name, CONCAT(ui.first_name, ' ', ui.last_name)) AS from_user_name,
                IF(cm.sender_type = 1, u.name, CONCAT(ui.first_name, ' ', ui.last_name)) AS to_user_name,
                IF(cm.sender_type = 0, ui.email, u.email) AS email, -- Include email
                cm.from_user_id,
                cm.to_user_id,
                cm.chat_message, 
                cm.timestamp, 
                cm.status
            FROM 
                chat_message cm
            LEFT JOIN 
                users u ON (cm.sender_type = 0 AND cm.from_user_id = u.id)
            LEFT JOIN 
                user_info ui ON (cm.sender_type = 1 AND cm.from_user_id = ui.user_id)
            LEFT JOIN 
                users u2 ON (cm.sender_type = 1 AND cm.to_user_id = u2.id)
            LEFT JOIN 
                user_info ui2 ON (cm.sender_type = 0 AND cm.to_user_id = ui2.user_id)
            WHERE 
                (cm.from_user_id = ? AND cm.to_user_id = ?)
                OR 
                (cm.from_user_id = ? AND cm.to_user_id = ?)
            ORDER BY 
                cm.timestamp ASC
        ";

        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('iiii', $this->from_user_id, $this->to_user_id, $this->to_user_id, $this->from_user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Store the chat messages in an array
        $chat_messages = [];
        while ($row = $result->fetch_assoc()) {
            $chat_messages[] = [
                'from_user_id' => $row['from_user_id'],
                'to_user_id' => $row['to_user_id'],
                'email' => $row['email'],  // Store the email
                'message' => $row['chat_message'],
                'timestamp' => $row['timestamp'],
                'status' => $row['status']
            ];
        }

        $stmt->close();
        return $chat_messages;  // Return the array of chat messages
    }
    function save_chat()
    {
        $query = "INSERT INTO chat_message (from_user_id, to_user_id, chat_message, timestamp, status, sender_type) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect->prepare($query);
        if (!$stmt) {
            // Handle errors with preparing the statement
            error_log("Prepare failed: (" . $this->connect->errno . ") " . $this->connect->error);
            return false;
        }
        if($this->role == 'admin'){
            $this->sender_type = 0;
        }else{
            $this->sender_type = 1;
        }

        // Bind parameters
        $stmt->bind_param(
            "iissii", 
            $this->from_user_id, 
            $this->to_user_id, 
            $this->chat_message, 
            $this->timestamp, 
            $this->status, 
            $this->sender_type
        );

        // Execute the statement
        $execute = $stmt->execute();
        if (!$execute) {
            // Handle errors with execution
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        // Close the statement
        $stmt->close();

        return $execute;
    }
    function update_chat_status()
    {
        $query = "UPDATE chat_message SET status = ? WHERE chat_message_id = ?";
        $stmt = $this->connect->prepare($query);
        $stmt->bind_param("ii", $this->status, $this->chat_message_id);
        $stmt->execute();
        $stmt->close();
    }
}
