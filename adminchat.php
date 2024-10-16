<?php
include __DIR__ . '/database/ChatUser.php';
$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

if ($role == 'admin') {
    $userid = $_SESSION['login_id'];
} else if ($role == 'customer') {
    $userid = $_SESSION['login_user_id'];
} else {
    $userid = 0;
}

require('database/ChatRooms.php');

$chat_object = new ChatRooms();
$user_object = new ChatUser();

$chat_data = $chat_object->get_all_chat_data();
$users = $chat_object->showscustomerinquries();

//get usertoken for websocket
$usertoken = md5(uniqid()); //generate unique token
$user_object->setUserToken($usertoken);
$user_object->setUserId($userid);
$user_object->setRole($role);
// . $userid . "_" . Date('YmdHis') . "_" . rand(1000, 9999)
$user_token = $userid . "_" . Date('YmdHis') . "_" . rand(1000, 9999);




$link_webSocket = "ws://localhost:8080?token=" . $user_token;
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Chat</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        /* Ensure html and body take up full height and have no margin */


        /* Make the chat-container take up full height and width */
        .chat-container {
            display: flex;
            height: 100%;
            width: 100%;
            border-radius: 0;
            /* Remove border-radius for full-page */
            overflow: hidden;
            box-shadow: none;
            /* Remove box-shadow for a cleaner full-page look */
            background: white;
        }

        /* Adjust customer list to take full height and allow for better responsiveness */
        .customer-list {
            width: 30%;
            min-width: 250px;
            /* Ensure a minimum width for smaller screens */
            padding: 20px;
            border-right: 1px solid #e0e0e0;
            background: #f7f9fc;
            overflow-y: auto;
        }

        .customer-list h3 {
            margin: 0 0 20px;
            font-weight: 500;
            color: #333;
            text-align: center;
        }

        .customer {
            padding: 12px;
            margin: 5px 0;
            border-radius: 5px;
            background: #ffffff;
            transition: background 0.3s;
            cursor: pointer;
            text-align: center;
        }

        .customer:hover {
            background: #e1e7ff;
        }

        .customer.active {
            background: #d1e7dd;
            /* Light green background */
            border-left: 4px solid #0f5132;
            /* Optional: add a left border for emphasis */
        }

        /* Make chat-panel take remaining width and full height */
        .chat-panel {
            width: 70%;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .chat-header {
            padding: 20px;
            background: #6200ea;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            flex-shrink: 0;
            /* Prevent shrinking */
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f9f9f9;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
        }

        /* Updated Message Styling */
        .message-container {
            display: flex;
            margin: 10px 0;
        }

        .message-container.user {
            justify-content: flex-start;
        }

        .message-container.admin {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 70%;
            padding: 12px 20px;
            border-radius: 20px;
            position: relative;
            word-wrap: break-word;
            font-size: 16px;
        }

        .message-container.user .message-content {
            background-color: #f1f0f0;
            color: #333;
            border-top-left-radius: 0;
        }

        .message-container.admin .message-content {
            background-color: #6200ea;
            color: white;
            border-top-right-radius: 0;
        }

        .message-container.user .message-content::after {
            content: '';
            position: absolute;
            top: 10px;
            left: -10px;
            width: 0;
            height: 0;
            border-top: 10px solid transparent;
            border-right: 10px solid #f1f0f0;
            border-bottom: 10px solid transparent;
        }

        .message-container.admin .message-content::after {
            content: '';
            position: absolute;
            top: 10px;
            right: -10px;
            width: 0;
            height: 0;
            border-top: 10px solid transparent;
            border-left: 10px solid #6200ea;
            border-bottom: 10px solid transparent;
        }

        .chat-input {
            display: flex;
            padding: 20px;
            background: #ffffff;
            flex-shrink: 0;
            /* Prevent shrinking */
        }

        .chat-input input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 16px;
            transition: border 0.3s;
        }

        .chat-input input:focus {
            border-color: #6200ea;
            outline: none;
        }

        .chat-input button {
            padding: 12px 20px;
            border: none;
            background: #6200ea;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .chat-input button:hover {
            background: #3700b3;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .chat-container {
                flex-direction: column;
            }

            .customer-list {
                width: 100%;
                height: 150px;
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
            }

            .chat-panel {
                width: 100%;
                height: calc(100% - 150px);
            }
        }
    </style>
</head>

<body>
    <div class="chat-container">
        <!-- Customer List -->
        <div class="customer-list">
            <h3>Customers</h3>
            <?php foreach ($users as $user): ?>

                <?php
                $query = "SELECT * FROM user_info WHERE user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $user['userid']);
                $stmt->execute();
                $result = $stmt->get_result(); // Get the result from the query
                $result = $result->fetch_assoc(); // Fetch the data
                ?>
                <div class="customer" data-userid="<?php echo htmlspecialchars($user['userid'], ENT_QUOTES, 'UTF-8'); ?>" data-email="<?php echo htmlspecialchars($result['email'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($result['email'], ENT_QUOTES, 'UTF-8'); ?></div>

            <?php endforeach; ?>
            <!-- Add more customers dynamically -->
        </div>

        <!-- Chat Panel -->
        <div class="chat-panel">
            <div class="chat-header" id="chatHeader">Chat with User</div>
            <div class="chat-messages" id="chatMessages">
                <!-- Example Messages -->
                <!--
                <div class="message-container user">
                    <div class="message-content">
                        <p>Hello! How can I assist you today?</p>
                    </div>
                </div>
                <div class="message-container admin">
                    <div class="message-content">
                        <p>I need help with my account.</p>
                    </div>
                </div>
                -->
            </div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Type a message...">
                <button id="sendButton">Send</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            let chat = {
                userid: <?php echo json_encode($userid); ?>, // Safely pass PHP variable
                wslink: '<?php echo $link_webSocket; ?>',
                email: '',
                conn: null
            };
            var receiver_userid = ''; // Receiver user id
            var receiver_email = ''; // Receiver email

            // Initialize WebSocket connection
            chat.conn = new WebSocket(chat.wslink);

            chat.conn.onopen = function () {
                console.log("WebSocket connection established.");
            };

            chat.conn.onmessage = function (e) {
                var data = JSON.parse(e.data);
                if (data.role === 'user') {
                    displayMessage(data.msg, 'user'); // Display user message
                } else if (data.role === 'admin') {
                    displayMessage(data.msg, 'admin'); // Display admin message
                } else {
                    console.warn("Unknown role:", data.role);
                }
            };

            chat.conn.onclose = function () {
                console.log("WebSocket connection closed.");
            };

            // Handle customer selection
            $(document).on('click', '.customer', function () {
                receiver_userid = $(this).data('userid');
                receiver_email = $(this).data('email');
                chat.email = receiver_email;
                document.getElementById('chatHeader').textContent = 'Chat with ' + chat.email;

                var from_user_id = chat.userid;
                $.ajax({
                    url: "../action.php",
                    method: "POST",
                    data: {
                        action: 'fetch_chat',
                        to_user_id: receiver_userid,
                        from_user_id: from_user_id
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.error) {
                            $('#chatMessages').html('<p>' + escapeHtml(data.error) + '</p>');
                            return;
                        }
                        console.log(data);

                        if (data.length > 0) { // Fixed typo here
                            var html_data = '';
                            for (var count = 0; count < data.length; count++) {
                                var row_class = '';
                                var user_name = '';

                                if (data[count].to_user_id == receiver_userid) {
                                    row_class = 'message-container admin';
                                    user_name = chat.email;
                                    var message = data[count].message; // Adjust field name as per your DB
                                } else {
                                    row_class = 'message-container user';
                                    user_name = 'Me';
                                    var message = data[count].message;
                                }

                                html_data += `
                                    <div class="${row_class}">
                                        <div class="message-content">
                                            <p>${escapeHtml(message)}</p>
                                        </div>
                                    </div>
                                `;
                            }
                            $('#chatMessages').html(html_data);
                            // Scroll to the bottom after loading messages
                            $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
                        } else {
                            $('#chatMessages').html('<p>No messages yet. Start the conversation!</p>');
                        }

                        // Set the active customer
                        setActiveCustomer(this);
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("An error occurred while fetching chat history.");
                    }
                });
            });

            function setActiveCustomer(element) {
                // Remove 'active' class from all customers
                const customers = document.querySelectorAll('.customer');
                customers.forEach(function (customer) {
                    customer.classList.remove('active');
                });

                // Add 'active' class to the clicked customer
                element.classList.add('active');
            }

            function sendMessage() {
                const messageInput = document.getElementById('chatInput');
                const message = messageInput.value.trim();

                if (message === '') return;

                const data = {
                    role: 'admin', // Adjust role based on your application logic
                    userid: chat.userid,
                    to_user_id: receiver_userid,
                    msg: message,
                    command: 'Private'
                };

                if (chat.conn && chat.conn.readyState === WebSocket.OPEN) {
                    chat.conn.send(JSON.stringify(data));
                   // displayMessage(message, 'admin'); // Display sent message
                    messageInput.value = ''; // Clear input

                    // Send the message to the server via AJAX to store in the database
                    $.ajax({
                        url: "../action.php",
                        method: "POST",
                        data: {
                            action: 'send_message',
                            to_user_id: receiver_userid,
                            from_user_id: chat.userid,
                            message: message,
                            role: 'admin' // Pass the role
                        },
                        dataType: "json",
                        success: function (response) {
                            if(response.success){
                                console.log("Message sent and stored.");
                            } else if(response.error){
                                console.error("Error:", response.error);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Failed to store message:", error);
                        }
                    });

                } else {
                    alert("WebSocket connection is not open.");
                }
            }

            function displayMessage(message, sender) {
                const chatMessagesDiv = document.getElementById('chatMessages');

                // Create message container
                const messageContainer = document.createElement('div');
                messageContainer.classList.add('message-container', sender);

                // Create message content
                const messageContent = document.createElement('div');
                messageContent.classList.add('message-content');
                messageContent.innerHTML = `<p>${escapeHtml(message)}</p>`;

                // Append message content to container
                messageContainer.appendChild(messageContent);

                // Append container to chat messages
                chatMessagesDiv.appendChild(messageContainer);

                // Scroll to the bottom
                chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
            }

            // Add click event listener to send button
            document.getElementById('sendButton').onclick = sendMessage;

            // Allow sending message with Enter key
            document.getElementById('chatInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            // Escape HTML to prevent XSS
            function escapeHtml(text) {
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
        });
    </script>
</body>

</html>