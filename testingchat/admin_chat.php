<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>
    <h1>Admin Chat</h1>
    <div id="messages" style="height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 20px;"></div>
    <input type="text" id="admin-input" placeholder="Type a message..." />
    <button onclick="sendMessage()">Send</button>

    <script>
        var ws = new WebSocket('ws://localhost:8080?role=admin'); // Connect as admin
        var userId; // This will store the selected user's resourceId

        ws.onmessage = function(event) {
            var messagesDiv = document.getElementById('messages');
            messagesDiv.innerHTML += `<div><strong>User:</strong> ${event.data}</div>`;
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        };

        function sendMessage() {
            var message = document.getElementById('admin-input').value;
            if (message.trim() === '') return;

            ws.send(JSON.stringify({
                type: 'chat',
                recipient: userId, // Send the message to the selected user
                message: message
            }));

            document.getElementById('messages').innerHTML += `<div><strong>You:</strong> ${message}</div>`;
            document.getElementById('admin-input').value = '';
        }

        function setUserId(id) {
            userId = id; // Set the selected user's resourceId
        }
    </script>
</body>
</html>
