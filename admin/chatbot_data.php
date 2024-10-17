<?php
// Database connection
include 'db_connect.php';
include('header.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding or updating data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['section'], $_POST['title']) && !empty($_POST['title'])) {
        $section = $_POST['section'];
        $title = json_encode($_POST['title']); // Convert title array to JSON

        // Handle options: If options are not provided, default to an empty array
        $options = isset($_POST['options']) ? json_encode($_POST['options']) : json_encode([]); 

        // Handle URLs: If URLs are not provided, default to an empty array
        $urls = isset($_POST['urls']) ? json_encode($_POST['urls']) : json_encode([]); 

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
        echo "<script>alert('Section and Titles are required.');</script>";
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

$data = $conn->query("SELECT * FROM chatbot_data");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Chatbot Data</title>
    <style>
        .container {
            max-width: 900px;
            margin: auto;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #2980b9;
        }
        .input-group {
            display: flex;
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
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
        }
        @media (max-width: 768px) {
            .input-group {
                flex-direction: column;
            }
            .form-actions {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Chatbot Data</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label>Section:</label>
            <input type="text" name="section" value="<?php echo $section; ?>" required>

            <label>Titles:</label>
            <div id="title-container">
                <?php foreach ($titles as $title): ?>
                    <div class="input-group">
                        <textarea name="title[]" required><?php echo $title; ?></textarea>
                        <button type="button" onclick="removeField(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
                <button type="button" onclick="addField('title')">Add More Title</button>
            </div>

            <label>Options:</label>
            <div id="options-container">
                <?php foreach ($options as $option): ?>
                    <div class="input-group">
                        <textarea name="options[]"><?php echo $option; ?></textarea>
                        <button type="button" onclick="removeField(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
                <button type="button" onclick="addField('options')">Add More Option</button>
            </div>

            <div class="form-actions">
                <button type="submit" name="save">Save</button>
                <button type="submit" name="clear">Clear</button>
            </div>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Section</th>
                <th>Title</th>
                <th>Options</th>
                <th>Actions</th>
            </tr>
            <?php if ($data->num_rows > 0): ?>
                <?php while ($row = $data->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['section']; ?></td>
                        <td><?php echo implode('<br>', json_decode($row['title'], true)); ?></td>
                        <td><?php echo implode('<br>', json_decode($row['options'], true)); ?></td>
                        <td>
                            <a href="?page=chatbot_data&id=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="delete_chatbot.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No data available.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <script>
        function addField(section) {
            const container = document.getElementById(section + '-container');
            const inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group');

            const textarea = document.createElement('textarea');
            textarea.name = section + '[]';
            textarea.required = section === 'title'; // Required only for title

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = 'Remove';
            removeButton.onclick = function () {
                removeField(removeButton);
            };

            inputGroup.appendChild(textarea);
            inputGroup.appendChild(removeButton);
            container.insertBefore(inputGroup, container.lastElementChild);
        }

        function removeField(button) {
            const inputGroup = button.parentNode;
            inputGroup.remove();
        }
    </script>
</body>
</html>
