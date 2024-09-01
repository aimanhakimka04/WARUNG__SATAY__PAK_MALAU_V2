<?php
header('Content-Type: application/javascript');

include 'admin/db_connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch chatbot data
$sql = "SELECT section, title, options, urls FROM chatbot_data";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[$row['section']] = [
            "title" => json_decode($row['title'], true),  // Decode JSON string to array
            "options" => json_decode($row['options'], true),  // Decode JSON string to array
            "url" => [
                "more" => "#",  // Placeholder, adjust if necessary
                "link" => json_decode($row['urls'], true)  // Decode JSON string to array
            ]
        ];
    }
}

$conn->close();

// Convert array to JSON string
$jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// Manually format the JSON string to use unquoted keys
$formattedData = preg_replace('/"(\w+)"\s*:/', '$1:', $jsonData);

// Output the formatted JavaScript variable
echo "var data = " . $formattedData . ";";
?>
