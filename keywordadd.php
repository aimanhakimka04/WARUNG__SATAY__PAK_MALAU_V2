<?php
header('Content-Type: application/javascript');

include 'admin/db_connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch keywords from a database table (assuming a 'keywords' table)
$sql = "SELECT section FROM chatbot_data"; // Update to your actual table name
$result = $conn->query($sql);

$keywords = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $keywords[$row['section']] = $row['section'];  // Assuming keyword and value columns
    }
}

$conn->close();

// Convert array to JSON string
$jsonKeywords = json_encode($keywords, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// Manually format the JSON string to use unquoted keys
$formattedKeywords = preg_replace('/"(\w+)"\s*:/', '$1:', $jsonKeywords);

// Output the formatted JavaScript variable
echo "const keywords = " . $formattedKeywords . ";";
?>
