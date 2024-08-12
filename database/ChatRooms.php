<?php

use Google\Service\CloudControlsPartnerService\Console;

class ChatRooms
{
    private $chat_id;
    private $user_id;
    private $message;
    private $created_on;
    protected $connect;

    private $role;

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

    public function __construct()
    {
        ob_start(); // this will turn on output buffering  the buffering (temporary storage) of output before it is flushed (sent and discarded) to the browser (in a web context) or to the shell (on the command line).
        include 'admin/db_connect.php';

        $this->connect = $conn;
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
}
