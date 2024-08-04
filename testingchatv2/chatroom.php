<?php session_start(); ?>

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
        }
        .message.right {
            justify-content: flex-end;
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
        <div id="messages"></div>
        <div id="input-group">
            <input type="text" id="user-input" placeholder="Type a message..." />
            <button onclick="sendMessage()">Send</button>
        </div>
        <div id="button-group">
            <button onclick="connectToAgent()">Connect to Agent</button>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            var conn = new WebSocket('ws://localhost:8080');
            conn.onopen = function(e) {
                console.log("Connection established!");
            };
            
            conn.onmessage = function(e) {
                console.log(e.data);
            };
        })
    </script>
    </body>
</html>
