<?php
require '../vendor/autoload.php';

use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use React\EventLoop\Factory as LoopFactory;

$loop = LoopFactory::create();
$connector = new Connector($loop);

$connector('ws://localhost:80/testingchat/')->then(function(WebSocket $conn) {
    echo "Connected to the server.\n";

    $conn->on('message', function($msg) use ($conn) {
        echo "New message from client: $msg\n";
        $response = readline("Enter your message: ");
        $conn->send($response);
    });

    $conn->on('close', function($code = null, $reason = null) {
        echo "Connection closed ({$code} - {$reason})\n";
    });

}, function(\Exception $e) use ($loop) {
    echo "Could not connect: {$e->getMessage()}\n";
    $loop->stop();
});

$loop->run();
