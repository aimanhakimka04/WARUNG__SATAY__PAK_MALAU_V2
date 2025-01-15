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
        .chat-container {
            display: flex;
            height: 100%;
            width: 100%;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
            background: white;
        }

        .customer-list {
            width: 30%;
            min-width: 250px;
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
            border-left: 4px solid #0f5132;
        }

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
            position: relative;
        }

        .chat-header button {
            position: absolute;
            right: 20px;
            top: 20px;
            background: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f9f9f9;
            border-bottom: 1px solid #e0e0e0;
        }

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

        .chat-input {
            display: flex;
            padding: 20px;
            background: #ffffff;
        }

        .chat-input input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 16px;
        }

        .chat-input button {
            padding: 12px 20px;
            border: none;
            background: #6200ea;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
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
        </div>

        <!-- Chat Panel -->
        <div class="chat-panel">
            <div class="chat-header" id="chatHeader">Chat with User
                <button id="deleteConversation">Delete Chat</button>
            </div>
            <div class="chat-messages" id="chatMessages"></div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Type a message...">
                <button id="sendButton">Send</button>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            let chat = {
                userid: <?php echo json_encode($userid); ?>, 
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
                    displayMessage(data.msg, 'user');
                } else if (data.role === 'admin') {
                    displayMessage(data.msg, 'admin');
                }
            };

            chat.conn.onclose = function () {
                console.log("WebSocket connection closed.");
            };

            $(document).on('click', '.customer', function () {
                receiver_userid = $(this).data('userid');
                receiver_email = $(this).data('email');
                chat.email = receiver_email;

                // Update chat header without removing the delete button
                document.getElementById('chatHeader').innerHTML = 'Chat with ' + chat.email + '<button id="deleteConversation">Delete Chat</button>';

                // Re-attach event listener for the delete button
                $('#deleteConversation').off('click').on('click', function () {
                    if (receiver_userid) {
                        if (confirm('Are you sure you want to delete this conversation?')) {
                            $.ajax({
                                url: "../action.php",
                                method: "POST",
                                data: {
                                    action: 'delete_conversation',
                                    to_user_id: receiver_userid,
                                    from_user_id: chat.userid
                                },
                                dataType: "json",
                                success: function (response) {
                                    if (response.success) {
                                        $('#chatMessages').html('<p>Conversation deleted.</p>');
                                    } else if (response.error) {
                                        alert(response.error);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error("Failed to delete conversation:", error);
                                }
                            });
                        }
                    } else {
                        alert("No conversation selected.");
                    }
                });

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

                        if (data.length > 0) {
                            var html = '';
                            for (var i = 0; i < data.length; i++) {
                                html += '<div class="message-container ' + (data[i].role === 'admin' ? 'admin' : 'user') + '">';
                                html += '<div class="message-content">' + escapeHtml(data[i].message) + '</div>';
                                html += '</div>';
                            }
                            $('#chatMessages').html(html);
                        } else {
                            $('#chatMessages').html('<p>No chat messages found.</p>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Failed to fetch chat:", error);
                    }
                });
            });

            $('#sendButton').on('click', function () {
                var message = $('#chatInput').val();
                if (message.trim() === '') {
                    return;
                }

                var data = {
                    role: 'admin',
                    msg: message,
                    to_user_id: receiver_userid,
                    from_user_id: chat.userid
                };
                chat.conn.send(JSON.stringify(data));
                $('#chatInput').val('');
                displayMessage(message, 'admin');
            });

            function displayMessage(message, role) {
                var container = $('<div class="message-container ' + role + '"></div>');
                var content = $('<div class="message-content"></div>').text(message);
                container.append(content);
                $('#chatMessages').append(container);
            }

            function escapeHtml(text) {
                return $('<div>').text(text).html();
            }
        });
    </script>
</body>

</html>
