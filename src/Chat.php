<?php
namespace MyApp;


use ChatRooms;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require  __DIR__."/../database/ChatRooms.php";


class Chat implements MessageComponentInterface
{
    protected $clients;

    protected $clientIds;

    protected $connect;

    

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->clientIds = [];
        require dirname(__DIR__) . "/admin/db_connect.php";
        ob_start();
        $this->connect = $conn;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Assign a unique ID to the new connection
        $clientId = uniqid();
        $this->clientIds[$conn->resourceId] = $clientId;

        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId}, ID: {$clientId})\n";
    }
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;

        $data = json_decode($msg, true);

        $chat_object = new ChatRooms();
        $data['client_id'] = $this->clientIds[$from->resourceId];
        $chat_object->setUserId($data['userid']);
        $chat_object->setMessage($data['msg']);
        $chat_object->setCreatedOn(date('Y-m-d H:i:s'));
        $chat_object->setRole($data['role']);
        $chat_object->save_chat();
        $userid = $data['userid'];
        $role = $data['role']; 
        // Include the unique client ID in the message
     
            
        //get user data by userid
        if($role == 'admin'){
            $query = "SELECT * FROM users WHERE id = $userid";
            $stmt = $this->connect->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $data['usremail'] = $row['email'];
            }
        else{
                $query = "SELECT * FROM user_info WHERE user_id = $userid";
                $stmt = $this->connect->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $data['usremail'] = $row['email'];
            }

        foreach ($this->clients as $client) {
            if ($from == $client) {
                $data['from'] = 'You';
            } else {
                $data['from'] = 'Other';
            }
            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $clientId = $this->clientIds[$conn->resourceId] ?? 'Unknown';
        unset($this->clientIds[$conn->resourceId]);

        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} (ID: {$clientId}) has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
