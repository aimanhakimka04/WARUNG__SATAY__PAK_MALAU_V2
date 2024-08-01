<?php
include '../admin/db_connect.php';
session_start();

$order_id = $_SESSION['order_id'];
$qryRider = $conn->query("SELECT * FROM rider WHERE order_id = $order_id");
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        transition: background-color 0.3s;
    }

    .custom-file-upload:hover {
        background-color: #0056b3;
    }

    .custom-file-upload i {
        margin-right: 8px;
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
        transition: background-color 0.3s;
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

    #fileInput {
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

                const removeBtn = document.createElement('button');
                removeBtn.className = 'remove-btn';
                removeBtn.textContent = 'x';
                removeBtn.onclick = function() {
                    const index = selectedFiles.indexOf(file);
                    if (index > -1) {
                        selectedFiles.splice(index, 1);
                    }
                    img.parentElement.remove();
                };

                const preview = document.createElement('div');
                preview.className = 'image-container';
                preview.appendChild(img);
                preview.appendChild(removeBtn);

                document.getElementById('imagePreview').appendChild(preview);
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
        xhr.open('POST', 'completedeliver.php', true);
        xhr.onload = function() {
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
    <div class="cdcontainer">
        <h1>Order Delivered</h1>
        <div class="alert alert-danger alert-container" id="alertMessage">
            Please upload at least one image.
        </div>
        <form id="uploadForm" onsubmit="submitForm(event)">
            <div class="cdform-group">
                <label for="orderId">Order ID</label>
                <input type="text" id="orderId" name="orderId" value="<?php echo $order_id ?>" disabled>
            </div>
            <div class="cdform-group">
                <label for="fileInput"><span style="color:red;">*</span>Image Proof</label>
                <label for="fileInput" class="custom-file-upload"><i class="fas fa-cloud-upload-alt"></i><b>Choose Image</b></label>
                <input type="file" id="fileInput" name="fileInput" accept="image/*" onchange="handleFileSelect(event)">
            </div>
            <div class="image-preview" id="imagePreview"></div>
            <input type="submit" value="Upload" class="btn-submit">
            <p class="note">* You can send proof images more than once *</p>
        </form>
    </div>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDirectory = 'images/proofimage/' . $order_id . '/';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true); // Create the directory if it doesn't exist
    }

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $order_id = $_SESSION['order_id'];
        $quantity_num = $key + 1;
        $file_name = $order_id . '_' . $quantity_num . '.' . pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION); // Rename the file to include the order ID and quantity number
        $targetFilePath = $uploadDirectory . $file_name;

        if (move_uploaded_file($tmp_name, $targetFilePath)) {
            echo "The file $file_name has been uploaded successfully.<br>";
        } else {
            echo "Sorry, there was an error uploading $file_name.<br>";
        }
    }
    // upload all image quantity and status to database


    $stmt = $conn->prepare("UPDATE rider SET status_delivery = 2, complete_proof = ? WHERE order_id = ?");
    $stmt->bind_param("si", $quantity_num, $order_id);
    $stmt1 = $conn->prepare("UPDATE orders SET status = 4 WHERE id = ?");
    $stmt1->bind_param("i", $order_id);
    $save = $stmt1->execute();

    
    if ($stmt->execute() ) {
        if ($save) {
            echo "<script>alert('Order has been delivered successfully!');</script>";
        } else {
            echo "<script>alert('There was an error updating order status.');</script>";
        }
    } else {
        echo "<script>alert('There was an error updating delivery order.');</script>";
    }
    $stmt->close();
    $conn->close();
}
?>