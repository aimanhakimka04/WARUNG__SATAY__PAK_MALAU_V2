<?php

use Google\Service\CloudControlsPartnerService\Console;

session_start();

$role = $_SESSION['user_role'];
if ($role == 'admin') {
    $userid = $_SESSION['login_id'];
} else if ($role == 'customer') {
    $userid = $_SESSION['login_user_id'];
}

require('database/ChatRooms.php');

$chat_object = new ChatRooms();

$chat_data = $chat_object->get_all_chat_data();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #chat {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        #messages {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #fafafa;
        }

        .message {
            margin: 10px 0;
            display: flex;
            justify-content: flex-start;
            flex-direction: column;
        }

        .message.right {
            align-items: flex-end;
        }

        .message.left {
            align-items: flex-start;
        }

        .bubble {
            max-width: 70%;
            padding: 10px;
            border-radius: 15px;
            position: relative;
            font-size: 14px;
        }

        .bubble:before {
            content: "";
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
        }

        .bubble.right {
            background-color: #007BFF;
            color: #fff;
            align-self: flex-end;
        }

        .bubble.right:before {
            top: 0;
            right: -10px;
            border-width: 10px 0 10px 10px;
            border-color: transparent transparent transparent #007BFF;
        }

        .bubble.left {
            background-color: #e0e0e0;
            color: #333;
            align-self: flex-start;
        }

        .bubble.left:before {
            top: 0;
            left: -10px;
            border-width: 10px 10px 10px 0;
            border-color: transparent #e0e0e0 transparent transparent;
        }

        .timestamp {
            font-size: 10px;
            color: #888;
            margin-top: 5px;
        }

        input[type="text"] {
            width: calc(100% - 100px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        #button-group {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div id="chat">
        <h1>Chatbot</h1>
        <div id="messages">
            <?php
            foreach ($chat_data as $row) {
                if($userid == $row['userid'] && $role == $row['role_user']) {
                    echo '<div class="message right"><div class="bubble right"><strong>You:</strong> ' . $row['msg'] . '</div><div class="timestamp">' . $row['created_on'] . '</div></div>';
                } else {
                    echo '<div class="message left"><div class="bubble left"><strong>testing user:</strong> ' . $row['msg'] . '</div><div class="timestamp">' . $row['created_on'] . '</div></div>';
                    //print in  console userid row userid
                    echo '<script>console.log('.$userid.')</script>';
                    echo '<script>console.log('.$row['userid'].')</script>';
                    //role user
                    echo '<script>console.log("'.$role.'")</script>';
                    echo '<script>console.log("'.$row['role_user'].'")</script>';
                }
            }

            ?>
        </div>

        <div id="input-group">
            <input type="text" id="user-input" placeholder="Type a message..." />
            <button onclick="sendMessage()">Send</button>
        </div>
        <div id="button-group">
            <button onclick="connectToAgent()">Connect to Agent</button>
        </div>
    </div>

    <script>
        const userid = <?php echo $userid; ?>;
        const role = "<?php echo $role; ?>";

        function getWebSocketURL() {
            const hostname = window.location.hostname;
            if (hostname === 'localhost') {
                return 'ws://localhost:8080';
            } else {
                return 'wss://70bc-183-78-114-120.ngrok-free.app';
            }
        }

        // Generate a random client ID
        function generateClientId() {
            return 'client-' + Math.random().toString(36).substr(2, 9);
        }

        const clientId = generateClientId();
        var conn = new WebSocket(getWebSocketURL());

        conn.onopen = function(e) {
            console.log("Connection established!");
        };

        conn.onmessage = function(e) {
            console.log(e.data);

            var data = JSON.parse(e.data);

            const messagesDiv = document.getElementById('messages');
            const timestamp = new Date().toLocaleTimeString();

            if (data.userid === userid) {
                messagesDiv.innerHTML += `<div class="message right"><div class="bubble right"><strong>You:</strong> ${data.msg}</div><div class="timestamp">${timestamp}</div></div>`;
            } else {
                messagesDiv.innerHTML += `<div class="message left"><div class="bubble left"><strong>${data.usremail}:</strong> ${data.msg}</div><div class="timestamp">${timestamp}</div></div>`;
            }

            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        };

        document.getElementById('user-input').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        });

        function sendMessage() {
            const userInput = document.getElementById('user-input').value;
            const timestamp = new Date().toLocaleTimeString();

            if (userInput.trim() === '') return;

            var data = {
                role: role,
                userid: userid,
                clientId: clientId,
                msg: userInput
            }

            conn.send(JSON.stringify(data));

            const messagesDiv = document.getElementById('messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
            document.getElementById('user-input').value = '';
        }

        function connectToAgent() {
            // Your connect to agent logic here
        }
    </script>
</body>

</html>