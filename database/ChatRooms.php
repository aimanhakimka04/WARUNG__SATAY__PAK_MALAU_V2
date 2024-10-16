<?php
//start session on web page

use Google\Service\CloudControlsPartnerService\Console;

class ChatRooms
{
    private $chat_id;
    private $user_id;
    private $message;
    private $created_on;
    protected $connect;

    private $role;
    private $role_number;
    private $link;
    private $staffid;
    private $status;
    private $user_token;

    private $connection_id;
    public function setChatId($chat_id)
    {
        $this->chat_id = $chat_id;
    }

    function getChatId()
    {
        return $this->chat_id;
    }

    function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    function getUserId()
    {
        return $this->user_id;
    }
    function setMessage($message)
    {
        $this->message = $message;
    }
    function getMessage()
    {
        return $this->message;
    }

    function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }
    function getCreatedOn()
    {
        return $this->created_on;
    }

    function setRole($role)
    {
        $this->role = $role;
    }
    function getRole()
    {
        return $this->role;
    }
    public function setLink($link)
    {
        $this->link = $link;
    }

    function getLink()
    {
        return $this->link;
    }

    public function setStaffId($staffid)
    {
        $this->staffid = $staffid;
    }

    function getStaffId()
    {
        return $this->staffid;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    function getStatus()
    {
        return $this->status;
    }

    public function setUserToken($user_token)
    {
        $this->user_token = $user_token;
    }

    function getUserToken()
    {
        return $this->user_token;
    }

    public function setConnectionId($connection_id)
    {
        $this->connection_id = $connection_id;
    }

    function getConnectionId()
    {
        return $this->connection_id;
    }
    public function __construct()
    {
        ob_start(); // this will turn on output buffering  the buffering (temporary storage) of output before it is flushed (sent and discarded) to the browser (in a web context) or to the shell (on the command line).
        include __DIR__ . '/../admin/db_connect.php';

        $this->connect = $conn;

        // Check if the connection was successful
        if ($this->connect->connect_error) {
            die("Connection failed: " . $this->connect->connect_error);
        }
    }

    function __destruct()
    { // this function will be called automatically at the end of the class
        $this->connect->close();
        ob_end_flush();
    }
    function save_chat()
    {
        $query = "INSERT INTO chatrooms(userid, msg, created_on, role_user) VALUES(?, ?, ?, ?)"; //: is a placeholder for the actual value from the user
        $stmt = $this->connect->prepare($query);
        $stmt->bind_param("isss", $this->user_id, $this->message, $this->created_on, $this->role);
        $stmt->execute();
        $stmt->close();
    }
    function get_all_chat_data()
    {
        $query = "SELECT * FROM chatrooms ORDER BY id ASC"; //ASC is used to sort the data in ascending order
        $stmt = $this->connect->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;

        // how to use the array data in the index.php file
        // $chat_object = new ChatRooms();
        // $chat_data = $chat_object->get_all_chat_data();
        // foreach($chat_data as $row)
        // {
        //     echo $row['msg'];
        // }

    }
    // Save the WebSocket link along with other required fields
    function save_websocket_link()
{
    //if role is admin, set rolenumber to 1
    if ($this->role == 'admin') {
        $this->role_number = 1;
    } else {
        $this->role_number = 0;
    }
    $count = null;
    // First, check if the userid already exists
    $checkQuery = "SELECT COUNT(*) FROM chatsecurity WHERE userid = ? AND role = ?";
    $checkStmt = $this->connect->prepare($checkQuery);
    $checkStmt->bind_param("ii", $this->user_id, $this->role_number);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    

    if ($count > 0) {
        // Update if userid exists
        $query = "UPDATE chatsecurity SET link = ?, user_token = ?, staffid = ?, status = ?, created_at = NOW() WHERE userid = ? AND role = ?";
        $stmt = $this->connect->prepare($query);
        $stmt->bind_param("ssisii", $this->link, $this->user_token, $this->staffid, $this->status, $this->user_id, $this->role_number);
    } else {
        // Insert if userid does not exist
        $query = "INSERT INTO chatsecurity(userid, link, user_token, staffid, status, created_at, role) VALUES(?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $this->connect->prepare($query);
        $stmt->bind_param("issisi", $this->user_id, $this->link, $this->user_token, $this->staffid, $this->status, $this->role_number);
    }
    if ($stmt->execute()) {
        $stmt->close();
        return true;  // Successful insertion or update
    } else {
        $stmt->close();
        return false; // Failed operation
    }
}
function update_connection_id(){

    $query = "UPDATE chatsecurity SET user_connection_id = ? WHERE user_token = ?";
    $stmt = $this->connect->prepare($query);
    $stmt->bind_param("is", $this->connection_id, $this->user_token);
    if($stmt->execute()){
        $stmt->close();
        return true;
    }else{
        $stmt->close();
        return false;
    }
}



    // Example of how you can retrieve and filter chat room data
    function get_all_websocket_data()
    {
        
        $query = "SELECT * FROM chatsecurity";
        $stmt = $this->connect->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $stmt->close();
        return $users;
    }
    function get_all_websocket_data_id()
    {
        
        $query = "SELECT * FROM chatsecurity where userid = ?";
        $stmt = $this->connect->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $stmt->close();
        return $users;
    }

    public function checkConnection()
    {
        if ($this->connect) {
            return true; // Connection successful
        } else {
            return false; // Connection failed
        }
    }
    public function showscustomerinquries()
    {
        $query = "SELECT * FROM chatsecurity where role = 0 and status = 0 OR status = 1";
        $stmt = $this->connect->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $stmt->close();
        return $users;
    }

  
}
