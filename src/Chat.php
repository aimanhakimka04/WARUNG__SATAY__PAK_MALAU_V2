<?php

namespace MyApp;

session_start();

use ChatRooms;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require  dirname(__DIR__) . "/database/ChatRooms.php";
require dirname(__DIR__) . "/database/ChatUser.php";
require dirname(__DIR__) . "/database/PrivateChat.php"; 


class Chat implements MessageComponentInterface
{

    protected $clients;

    protected $clientIds;

    protected $connect;



    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo 'Server Started';
        $this->clientIds = [];
        require dirname(__DIR__) . "/admin/db_connect.php";

        $this->connect = $conn;
    }

    public function onOpen(ConnectionInterface $connsocket)
    {
        echo "\nServer User Started";
        // Assign a unique ID to the new connection

        $this->clients->attach($connsocket); // attach the connection to the clients object
        $querystring = $connsocket->httpRequest->getUri()->getQuery(); // get the query string from the connection request
        parse_str($querystring, $queryarray); // parse the query string into an array
        $user_object = new ChatRooms(); // \ is used to access the global namespace
        $user_object->setUserToken($queryarray['token']); // set the user token from the query string
        $user_object->setConnectionId($connsocket->resourceId);

        //print in console the connection id
        $user_object->update_connection_id();

        echo "\nNew connection! ({$connsocket->resourceId})\n";
    }
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s' . "\n",
            $from->resourceId,
            $msg,
            $numRecv,
            $numRecv == 1 ? '' : 's'
        );


        $data = json_decode($msg, true);

        if ($data['command'] == 'Private') {
            //private chat
            $private_chat_object = new \PrivateChat();
            $private_chat_object->setToUserId($data['to_user_id']);
            $private_chat_object->setFromUserId($data['userid']);
            $private_chat_object->setChatMessage($data['msg']);
            $private_chat_object->setTimestamp(date('Y-m-d H:i:s'));
            $private_chat_object->setStatus(1);
            $private_chat_object->setRole($data['role']);
            $chat_message_id = $private_chat_object->save_chat();
            $user_object = new ChatRooms();
            $user_object->setUserId($data['userid']);
            $sender_user_data = $user_object->get_all_websocket_data_id();
            $user_object->setUserId($data['to_user_id']);
            $receiver_user_data = $user_object->get_all_websocket_data_id();
            //print array 

            print_r($sender_user_data);
            print_r($receiver_user_data);
            // $sender_user_connection_id = $sender_user_data['user_connection_id'];
           $receiver_user_connection_id = $receiver_user_data[0]['user_connection_id'];
            echo $receiver_user_connection_id;
            foreach($this->clients as $client){
                if($from == $client){
                    $data['from'] = 'Me';
                    $client->send(json_encode($data));
                }else{
                    $data['from'] = 'Other';
                    $client->send(json_encode($data));
                }
                if($client->resourceId == $receiver_user_connection_id || $from == $client){
                    $client->send(json_encode($data));
                }else{
                    $private_chat_object->setChatMessageId($chat_message_id);
                    $private_chat_object->update_chat_status();
                    
                }
            }

            


            


        } else {
            $chat_object = new ChatRooms();
            //$data['client_id'] = $this->clientIds[$from->resourceId];
            $chat_object->setUserId($data['userid']);
            $chat_object->setMessage($data['msg']);
            $chat_object->setCreatedOn(date('Y-m-d H:i:s'));
            $chat_object->setRole($data['role']);
            $chat_object->save_chat();
            $userid = $data['userid'];
            $role = $data['role'];
            // Include the unique client ID in the message


            //get user data by userid
            if ($role == 'admin') {
                $query = "SELECT * FROM users WHERE id = $userid";
                $stmt = $this->connect->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $data['usremail'] = $row['email'];
            } else {
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


        //if($client->resourceId == )
    }

    public function onClose(ConnectionInterface $connsocket)
    {
        $clientId = $this->clientIds[$connsocket->resourceId] ?? 'Unknown';
        unset($this->clientIds[$connsocket->resourceId]);

        $this->clients->detach($connsocket);

        echo "Connection {$connsocket->resourceId} (ID: {$clientId}) has disconnected\n";
    }

    public function onError(ConnectionInterface $connsocket, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $connsocket->close();
    }
}
