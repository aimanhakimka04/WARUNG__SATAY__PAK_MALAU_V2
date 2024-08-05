<?php
header('Content-Type: application/json'); //ini untuk set content type kepada json. contoh content type yang lain ialah text/html

// Database connection
$host = 'localhost';
$db = 'chatbot_db';
$user = 'root'; // Update with your database username
$pass = ''; // Update with your database password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$predefined_responses = [
    'hello' => 'Hello! How can I help you today?', // => adalah untuk assign value kepada key
    'hi' => 'Hi there! What can I do for you?',
    'how are you' => 'I am just a bot, but I am here to help you!',
    'bye' => 'Goodbye! Have a great day!',
    'connect to agent' => 'Connecting you to a live agent...',
    // Add more predefined responses here
];

function normalizeInput($input) {
    $words = explode(' ', $input);
    $normalized_words = array_unique($words);
    return implode(' ', $normalized_words);
}

function findClosestMatch($input, $responses) {
    $threshold = 3; // Adjust the threshold as needed
    $shortest_distance = null;
    $closest_response = '';

    foreach ($responses as $key => $value) {
        $distance = levenshtein($input, $key);

        if ($distance <= $threshold && ($shortest_distance === null || $distance < $shortest_distance)) {
            $shortest_distance = $distance;
            $closest_response = $value;
        }
    }

    return ($shortest_distance !== null) ? $closest_response : null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $raw_input = file_get_contents('php://input'); // Read the raw input
    $input = json_decode($raw_input, true); //true means the data will be returned as an associative array

    if ($input === null) {
        echo json_encode(['error' => 'Invalid JSON input']);
        exit;
    }

    $userMessage = strtolower(trim($input['message']));
    $normalizedMessage = normalizeInput($userMessage);
    $response = 'Sorry, I do not understand that.';
    $is_clear = 0;

    // Check for an exact match first
    if (isset($predefined_responses[$normalizedMessage])) {
        $response = $predefined_responses[$normalizedMessage];
        $is_clear = 1;
    } else {
        // Use Levenshtein distance to find the closest match
        $closest_response = findClosestMatch($normalizedMessage, $predefined_responses);

        if ($closest_response !== null) {
            $response = $closest_response;
            $is_clear = 1;
        } else {
            // Check database for response
            $stmt = $conn->prepare("SELECT bot_response FROM messages WHERE user_message = ?");
            $stmt->bind_param("s", $normalizedMessage);
            $stmt->execute();
            $stmt->bind_result($db_response);
            if ($stmt->fetch()) {
                $response = $db_response;
                $is_clear = 1;
            }
            $stmt->close();
        }
    }

    if ($userMessage === 'connect to agent') {
        // Insert logic to notify a live agent here
        // For example, store the request in the database
        $stmt = $conn->prepare("INSERT INTO agent_requests (user_message, status) VALUES (?, ?)");
        $stmt->bind_param("ss", $userMessage, $status);
        $status = 'pending';
        $stmt->execute();
        $stmt->close();
    }

    // Store the user message and bot response in the database
    $stmt = $conn->prepare("INSERT INTO messages (user_message, bot_response, is_clear) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $userMessage, $response, $is_clear);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['response' => $response]);
}

$conn->close();
?>
