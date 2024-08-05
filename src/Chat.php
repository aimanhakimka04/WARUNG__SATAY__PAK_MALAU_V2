<?php

namespace MyApp;

use ChatRooms;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . "/database/ChatRooms.php";
require dirname(__DIR__) . "/admin/db_connect.php";

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $clientIds;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->clientIds = [];
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
        $chat_object->setUserId($data['client_id']);
        $chat_object->setMessage($data['msg']);
        $chat_object->setCreatedOn(date('Y-m-d H:i:s'));
        $chat_object->save_chat();

        // Include the unique client ID in the message


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
