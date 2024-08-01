<?php
include '../admin/db_connect.php';

$order_id = 138;
$qryRider = $conn->query("SELECT * FROM rider WHERE order_id = $order_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDirectory = 'images/proofimage/';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    $uploadedFiles = [];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['images']['name'][$key]);
        $targetFilePath = $uploadDirectory . $file_name;

        if (move_uploaded_file($tmp_name, $targetFilePath)) {
            $uploadedFiles[] = $targetFilePath;
            echo "The file $file_name has been uploaded successfully.<br>";
        } else {
            echo "Sorry, there was an error uploading $file_name.<br>";
        }
    }

    if (!empty($uploadedFiles)) {
        $uploadedFilesJson = json_encode($uploadedFiles); // Convert the array to a JSON string
        $stmt = $conn->prepare("UPDATE rider SET status_delivery = 2, proof_of_delivery = ? WHERE order_id = ?");
        $stmt->bind_param('si', $uploadedFilesJson, $order_id);
        if ($stmt->execute()) {
            $stmt1 = $conn->prepare("UPDATE orders SET status = 5 WHERE id = ?");
            $stmt1->bind_param('i', $order_id);
            if ($stmt1->execute()) {
                echo "<div class='alert alert-success'>Order updated successfully!</div>";
                //page refresh
                echo "<meta http-equiv='refresh' content='1'>";
            } else {
                echo "<div class='alert alert-danger'>Error updating order: " . $stmt1->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error updating order: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>No valid files were uploaded.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivered Order Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .cdcontainer {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            align-items: center;
            margin: auto;
        }
        .cdcontainer h1 {
            font-size: 26px;
            margin-bottom: 20px;
            color: #333;
        }
        .cdform-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .cdform-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .cdform-group input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .custom-file-upload {
            display: inline-block;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 6px;
            background-color: #007bff;
            color: white;
            border: none;
            margin-top: 10px;
            font-size: 14px;
        }
        .custom-file-upload:hover {
            background-color: #0056b3;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            width: 100%;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 16px;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .image-preview {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
            justify-content: center;
        }
        .image-preview img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            margin: 5px;
            border-radius: 6px;
            border: 2px solid #ddd;
        }
        .image-preview .image-container {
            position: relative;
            display: inline-block;
        }
        .image-preview .image-container .remove-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            padding: 2px 5px;
            font-size: 12px;
        }
        #proofImages {
            display: none;
        }
        .note {
            color: #555;
            font-size: 12px;
            margin-top: 15px;
        }
        .alert-container {
            display: none;
            margin-top: 15px;
        }
        .modal-footer {
            display: none;
        }
    </style>
</head>
<body>

<div class="cdbody">
    <div class="cdcontainer">
        <h1>Order Delivered</h1>
        <div class="alert alert-danger alert-container" id="alertMessage">
            Please upload at least one image.
        </div>
        <form id="deliveredOrderForm" action="" method="POST" enctype="multipart/form-data">

            <div class="cdform-group">
                <label for="orderId">Order ID</label>
                <input type="text" id="orderId" name="orderId" value="<?php echo $order_id ?>" disabled>
            </div>
            <div class="cdform-group">
                <label for="fileInput">Image Proof</label>
                <label for="fileInput" class="custom-file-upload"><i class="fas fa-cloud-upload-alt"></i> Choose Image</label>
                <input type="file" name="images[]" id="fileInput" accept="image/*" multiple onchange="handleFileSelect(event)" required>
            </div>

            <div class="image-preview" id="imagePreview"></div>
            <button type="submit" name="submit" class="btn-submit">Submit</button>
            <p class="note">* You can send proof images more than once *</p>
        </form>
    </div>
</div>

<script>
    const proofImagesInput = document.getElementById('fileInput');
    const imagePreview = document.getElementById('imagePreview');
    const alertMessage = document.getElementById('alertMessage');
    const deliveredOrderForm = document.getElementById('deliveredOrderForm');

    proofImagesInput.addEventListener('change', function(event) {
        const files = event.target.files;
        imagePreview.innerHTML = '';

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageContainer = document.createElement('div');
                imageContainer.className = 'image-container';
                
                const img = document.createElement('img');
                img.src = e.target.result;

                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '&times;';
                removeBtn.className = 'remove-btn';
                removeBtn.onclick = function() {
                    imageContainer.remove();
                };

                imageContainer.appendChild(img);
                imageContainer.appendChild(removeBtn);
                imagePreview.appendChild(imageContainer);
            };
            reader.readAsDataURL(file);
        });

        // Clear the input value to allow re-uploading the same file if needed
        proofImagesInput.value = ''; 
    });

    deliveredOrderForm.addEventListener('submit', function(event) {
        if (!proofImagesInput.files.length) {
            alertMessage.style.display = 'block';
            event.preventDefault();
        } else {
            alertMessage.style.display = 'none';
        }
    });
</script>
</body>
</html>
