<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload Form</title>
    <style>
        .preview {
            display: inline-block;
            margin: 10px;
        }
        .preview img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
    <script>
        let selectedFiles = [];

        function handleFileSelect(event) {
            const files = event.target.files;

            for (const file of files) {
                selectedFiles.push(file);
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    const preview = document.createElement('div');
                    preview.className = 'preview';
                    preview.appendChild(img);
                    document.getElementById('previewContainer').appendChild(preview);
                }
                reader.readAsDataURL(file);
            }

            event.target.value = ''; // Clear the input so the same file can be selected again if needed
        }

        function submitForm(event) {
            event.preventDefault();
            const formData = new FormData();

            for (const file of selectedFiles) {
                formData.append('images[]', file);
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'test.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Files uploaded successfully!');
                } else {
                    alert('An error occurred!');
                }
            };
            xhr.send(formData);
        }
    </script>
</head>
<body>
    <h2>Upload Images</h2>
    <form id="uploadForm" onsubmit="submitForm(event)">
        <input type="file" id="fileInput" accept="image/*" onchange="handleFileSelect(event)">
        <br><br>
        <div id="previewContainer"></div>
        <br><br>
        <input type="submit" value="Upload">
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDirectory = 'images/proofimage/';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['images']['name'][$key]);
        $targetFilePath = $uploadDirectory . $file_name;

        if (move_uploaded_file($tmp_name, $targetFilePath)) {
            echo "The file $file_name has been uploaded successfully.<br>";
        } else {
            echo "Sorry, there was an error uploading $file_name.<br>";
        }
    }
}
?>
