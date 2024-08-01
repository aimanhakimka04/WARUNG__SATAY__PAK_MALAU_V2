<div style="margin-top:80px"></div>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Rating Page</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .custom-rating-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 50px auto;
        }

        .custom-stars img {
            width: 50px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .custom-stars img:hover {
            transform: scale(1.1);
        }

        .custom-rating-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .custom-rating-form textarea {
            resize: vertical;
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .custom-rating-form input[type="file"] {
            padding: 10px;
            font-size: 14px;
        }

        .custom-rating-form button {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .custom-rating-form button:hover {
            background-color: #0056b3;
        }

        #custom-img-preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            cursor: pointer;
        }

        /* Modal styles */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .custom-modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .custom-close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .custom-close:hover,
        .custom-close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Comments section */
        .comments-container {
            width: 90%;
            max-width: 1000px; /* Optional: Set a maximum width to prevent it from becoming too large on wide screens */
            margin: 0 auto; /* Centering the container */
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }




        .comment {
            width: 300px;
            height:auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        
        .comment img.user-img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        .comment-text {
            margin: 5px 0;
            word-wrap: break-word;
            width: 100%;
            text-align: left;
        }

        .comment img{
            width:230px;
            height:170px;
        }
        .comment img.comimg {
            cursor: pointer;
            border-radius: 10px;
            width: 230px;
            height: 170px;
            object-fit: cover; /* Ensures the image covers the entire area without distortion */

        }

        .ratestar img {
            width: 25px;
            height: 25px;
        }
    </style>
</head>
<body>

<div class="comments-container">
    <?php
    // Database connection assumed to be established in $conn
    $limit = 999;
    $page = (isset($_GET['_page']) && $_GET['_page'] > 0) ? $_GET['_page'] - 1 : 0;
    $offset = $page > 0 ? $page * $limit : 0;

    // Query to count total comments
    $all_comments_count = $conn->query("SELECT rate_id FROM rating WHERE status_comment = 2")->num_rows;
    $page_btn_count = ceil($all_comments_count / $limit);

    // Query to fetch comments based on the selected date (if any), rating, and pagination
    $qry = $conn->query("SELECT * FROM rating WHERE status_comment = 2 ORDER BY user_id ASC LIMIT $limit OFFSET $offset");

    while ($row = $qry->fetch_assoc()):
    ?>
    <div class="comment">
        <p class="comment-text">User: <?php echo $row['user_name'] ?></p>
        <p class="comment-text">Time: <?php echo $row['datentime'] ?></p>
        <p class="comment-text">Description: <?php echo $row['description'] ?></p>
        <div class="ratestar">
            <?php
            $rate = $row['rate'];
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rate) {
                    echo '<img src="admin/assets/img/star.png">';
                } else {
                    echo '<img src="admin/assets/img/star_empty.png">';
                }
            }
            ?>
        </div>
        <img src="../assets/img/<?php echo $row['img_comment'] ?>" class="comimg" onclick="expandImage(this)">

    </div>
    <?php
    endwhile;
    ?>
</div>

</body>
</html>