<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require '../vendor/autoload.php';

class Chat implements MessageComponentInterface {
    protected $clients;
    private $users = [];  // User connections
    private $admins = []; // Admin connections

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        $querystring = $conn->httpRequest->getUri()->getQuery(); //conn is the connection object it from the client it declare from 
        parse_str($querystring, $queryParams);

        if (isset($queryParams['role']) && $queryParams['role'] == 'admin') {
            $this->admins[$conn->resourceId] = $conn;
            echo "New admin connected: ({$conn->resourceId})\n";
        } else {
            $this->users[$conn->resourceId] = $conn;
            echo "New user connected: ({$conn->resourceId})\n";
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $msgData = json_decode($msg, true);

        if (isset($msgData['type']) && $msgData['type'] == 'chat') {
            $recipientId = $msgData['recipient'];
            $message = $msgData['message'];

            if (isset($this->users[$recipientId])) {
                $this->users[$recipientId]->send($message);
            } elseif (isset($this->admins[$recipientId])) {
                $this->admins[$recipientId]->send($message);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        if (isset($this->users[$conn->resourceId])) {
            unset($this->users[$conn->resourceId]);
        } elseif (isset($this->admins[$conn->resourceId])) {
            unset($this->admins[$conn->resourceId]);
        }

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

$server->run();
