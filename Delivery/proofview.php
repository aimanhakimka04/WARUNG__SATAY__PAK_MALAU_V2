<?php
include '../admin/db_connect.php';
session_start();

$order_id = $_GET['id'];
$uploadDirectory = 'images/proofimage/' . $order_id . '/';

function getImages($dir) {
    $images = array();
    if (is_dir($dir)) {
        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false) {
            if ($file !== '.' && $file !== '..') {
                $images[] = $dir . $file;
            }
        }
        closedir($handle);
    }
    return $images;
}

$images = getImages($uploadDirectory);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        panelbody {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .cdcontainer {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            text-align: center;
            align-items: center;
            margin: auto;
        }

        .cdcontainer h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        .image-preview {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
            justify-content: center;
        }

        .image-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin: 10px;
            border-radius: 10px;
            border: 3px solid #ddd;
            cursor: pointer;
        }

        .note {
            color: #777;
            font-size: 14px;
            margin-top: 20px;
        }

        /* Modal Styles */
        .unique-modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .unique-modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .unique-modal-content, #unique-caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {transform: scale(0)} 
            to {transform: scale(1)}
        }

        .unique-close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .unique-close:hover,
        .unique-close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        @media only screen and (max-width: 700px) {
            .unique-modal-content {
                width: 100%;
            }
        }
        .modal-footer {
            display: none;
        }
    </style>
</head>
<div class="panelbody">
    <div class="cdcontainer">
        <h1><i class="fas fa-images"></i> View Uploaded Images</h1>
        <div class="image-preview" id="imagePreview">
            <?php foreach ($images as $image): ?>
                <img src="<?php echo $image; ?>" alt="Proof Image" class="unique-hover-shadow">
            <?php endforeach; ?>
        </div>
        <p class="note">* All proof images uploaded for this order *</p>
    </div>

    <!-- The Modal -->
    <div id="uniqueModal" class="unique-modal">
        <span class="unique-close">&times;</span>
        <img class="unique-modal-content" id="uniqueImg01">
        <div id="unique-caption"></div>
    </div>

    <script>
        // Get the modal
        var uniqueModal = document.getElementById("uniqueModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var images = document.getElementsByClassName("unique-hover-shadow");
        var uniqueModalImg = document.getElementById("uniqueImg01");
        var uniqueCaptionText = document.getElementById("unique-caption");
        for (var i = 0; i < images.length; i++) {
            images[i].onclick = function() {
                uniqueModal.style.display = "block";
                uniqueModalImg.src = this.src;
                uniqueCaptionText.innerHTML = this.alt;
            }
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("unique-close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            uniqueModal.style.display = "none";
        }
    </script>
</div>
</html>
