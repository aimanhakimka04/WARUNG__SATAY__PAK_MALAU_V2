<?php
// Database connection
include 'db_connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding or updating data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['section'], $_POST['title'], $_POST['options']) && !empty($_POST['title']) && !empty($_POST['options'])) {
        $section = $_POST['section'];
        $title = json_encode($_POST['title']); // Convert title array to JSON
        $options = json_encode($_POST['options']); // Convert options array to JSON
        $urls = isset($_POST['urls']) ? json_encode($_POST['urls']) : json_encode([]); // Optional URL handling
        
        // Check if section already exists
        $stmt = $conn->prepare("SELECT id FROM chatbot_data WHERE section = ?");
        $stmt->bind_param("s", $section);
        $stmt->execute();
        $stmt->store_result();
        
        // Fetch the existing section ID if it exists
        $existingId = null;
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($existingId);
            $stmt->fetch();
        }

        $stmt->close();

        if (!empty($_POST['id'])) {
            // Check if it's the same record (for update scenario)
            if ($existingId && $existingId != $_POST['id']) {
                echo "<script>alert('Error: Section already exists in the database.');</script>";
            } else {
                // Update existing record
                $id = $_POST['id'];
                $stmt = $conn->prepare("UPDATE chatbot_data SET section=?, title=?, options=?, urls=? WHERE id=?");
                $stmt->bind_param("ssssi", $section, $title, $options, $urls, $id);
                
                if ($stmt->execute()) {
                    echo "<script>alert('Record updated successfully.');</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }

                $stmt->close();
            }
        } else {
            // Insert new record only if section does not already exist
            if ($existingId) {
                echo "<script>alert('Error: Section already exists in the database.');</script>";
            } else {
                $stmt = $conn->prepare("INSERT INTO chatbot_data (section, title, options, urls) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $section, $title, $options, $urls);
                
                if ($stmt->execute()) {
                    echo "<script>alert('Record saved successfully.');</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }

                $stmt->close();
            }
        }
    } else {
        echo "<script>alert('Section, Titles, and Options are required.');</script>";
    }
}

// Fetch data to display or edit
$id = '';
$section = '';
$titles = [];
$options = [];
$urls = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM chatbot_data WHERE id=$id");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $section = $row['section'];
        $titles = json_decode($row['title'], true);
        $options = json_decode($row['options'], true);
        $urls = json_decode($row['urls'], true);
    }
}

$result = $conn->query("SELECT * FROM chatbot_data");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Data Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1, h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            margin-bottom: 40px;
        }
        label {
            font-size: 16px;
            color: #34495e;
            margin-bottom: 10px;
            display: block;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
            outline: none;
        }
        textarea {
            height: 70px;
            resize: none;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background-color: #2980b9;
        }
        .input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .input-group button {
            margin-left: 10px;
            background-color: #e74c3c;
        }
        .input-group button:hover {
            background-color: #c0392b;
        }
        table {
            width: 90%;
            margin-bottom: 40px;
            background-color: white;
            border-collapse: collapse;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        td {
            background-color: #ecf0f1;
        }
        td a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        td a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            form, table {
                width: 95%;
            }
        }
    </style>
    <script>
        function addField(section) {
            const container = document.getElementById(section + '-container');
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group';

            const textarea = document.createElement('textarea');
            textarea.name = section + '[]';
            textarea.required = true;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.innerText = 'Remove';
            removeButton.onclick = function () {
                container.removeChild(inputGroup);
            };

            inputGroup.appendChild(textarea);
            inputGroup.appendChild(removeButton);
            container.appendChild(inputGroup);
        }

        function removeField(button) {
            const container = button.closest('.input-group').parentElement;
            container.removeChild(button.closest('.input-group'));
        }
    </script>
</head>
<body>
    <h1>Chatbot Data Management</h1>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label for="section">Section:</label>
        <input type="text" name="section" value="<?php echo htmlspecialchars($section); ?>" required>

        <label for="title">Titles:</label>
        <div id="title-container">
            <?php if (count($titles) > 0): ?>
                <?php foreach ($titles as $title): ?>
                    <div class="input-group">
                        <textarea name="title[]" required><?php echo htmlspecialchars($title); ?></textarea>
                        <button type="button" onclick="removeField(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="input-group">
                    <textarea name="title[]" required></textarea>
                </div>
            <?php endif; ?>
            <button type="button" onclick="addField('title')">Add More Title</button>
        </div>

        <label for="options">Options:</label>
        <div id="options-container">
            <?php if (count($options) > 0): ?>
                <?php foreach ($options as $option): ?>
                    <div class="input-group">
                        <textarea name="options[]" required><?php echo htmlspecialchars($option); ?></textarea>
                        <button type="button" onclick="removeField(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="input-group">
                    <textarea name="options[]" required></textarea>
                </div>
            <?php endif; ?>
            <button type="button" onclick="addField('options')">Add More Option</button>
        </div>

        <label for="urls">URLs (Optional):</label>
        <div id="urls-container">
            <?php if (count($urls) > 0): ?>
                <?php foreach ($urls as $url): ?>
                    <div class="input-group">
                        <textarea name="urls[]"><?php echo htmlspecialchars($url); ?></textarea>
                        <button type="button" onclick="removeField(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="input-group">
                    <textarea name="urls[]"></textarea>
                </div>
            <?php endif; ?>
            <button type="button" onclick="addField('urls')">Add More URL</button>
        </div>

        <button type="submit">Save</button>
    </form>

    <h2>Existing Data</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Section</th>
            <th>Title</th>
            <th>Options</th>
            <th>URLs</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) {
            $titles = json_decode($row['title'], true);
            $options = json_decode($row['options'], true);
            $urls = json_decode($row['urls'], true);
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['section']); ?></td>
            <td>
                <?php foreach ($titles as $title) {
                    echo htmlspecialchars($title) . "<br>";
                } ?>
            </td>
            <td>
                <?php foreach ($options as $option) {
                    echo htmlspecialchars($option) . "<br>";
                } ?>
            </td>
            <td>
                <?php if (!empty($urls)) {
                    foreach ($urls as $url) {
                        echo htmlspecialchars($url) . "<br>";
                    }
                } else {
                    echo "No URLs provided.";
                } ?>
            </td>
            <td>
                <a href="?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="delete_chatbot.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
