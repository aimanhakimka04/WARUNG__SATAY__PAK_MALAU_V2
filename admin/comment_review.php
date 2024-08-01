<?php
    include 'db_connect.php';

    // Check if a date is selected
    $selectedDate = isset($_GET['selected_date']) ? $_GET['selected_date'] : null;

    // Check if a rating is selected
    $selectedRating = isset($_GET['selected_rating']) ? $_GET['selected_rating'] : null;

    // Construct the SQL query based on whether a date is selected or not
    if ($selectedDate) {
        $whereClause = "WHERE DATE(datentime) = '$selectedDate'";
    } else {
        $whereClause = "";
    }

    // Append the rating condition to the WHERE clause if a rating is selected
    if ($selectedRating) {
        $selectedRatings = explode(',', $selectedRating);
        $ratingConditions = [];
        foreach ($selectedRatings as $rating) {
            $ratingConditions[] = "rate = '$rating'";
        }
        $ratingCondition = implode(' OR ', $ratingConditions);
        // Check if $whereClause already has content
        if ($whereClause) {
            // If $whereClause already has content, append the rating condition with 'AND'
            $whereClause .= " AND ($ratingCondition)";
        } else {
            // If $whereClause is empty, start with 'WHERE'
            $whereClause = "WHERE ($ratingCondition)";
        }
    }


    $limit = 10;
    $page = (isset($_GET['_page']) && $_GET['_page'] > 0) ? $_GET['_page'] - 1 : 0;
    $offset = $page > 0 ? $page * $limit : 0;

    // Query to count total comments
    $all_comments_count = $conn->query("SELECT rate_id FROM rating $whereClause")->num_rows;
    $page_btn_count = ceil($all_comments_count / $limit);

    // Query to fetch comments based on the selected date (if any), rating, and pagination
    $qry = $conn->query("SELECT * FROM rating $whereClause ORDER BY user_id ASC LIMIT $limit OFFSET $offset");
?>


<html>
<head>
    <style>
        /* CSS for rate div */
        .rate {
            padding: 50px !important;
        }

        /* CSS for pan div */
        .pan {
            position: relative;
        }

        /* CSS for datepicker button */
        #datepicker-btn {
            width: 150px !important;
            height: 35px !important;
            margin-top: -115px  !important;
            margin-bottom: 20px !important;
            margin-left: 1150px !important;
            background-color: rgba(241, 241, 241, 0.616) !important;
            border-radius: 10px !important;
        }

        /* CSS for datepicker div */
        #datepicker {
            display: none;
            position: absolute;
            z-index: 999;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            margin-left: 1155px;
        }

        /* CSS for comment div */
        .comment {
            border: 1px solid rgb(232, 232, 232);
            border-radius: 10px;
            padding: 20px;
            height: 170px;
            width: 1450px;
            background-color: white;
            margin-top: 5px;
        }

        /* CSS for user image 
        .user-img {
            border-radius: 0px;
            height: 70px;
            width: 70px;
            border: 0px solid;
            margin-top: 0px;
        }*/

        /* CSS for comment text */
        .comment-text1 {
            font-size: 20px;
            margin-left: 100px;
            margin-top: -40px !important;

        }
        .comment-text2 {
            font-size: 20px;
            margin-left: 100px;
            margin-top: -20px !important;
        }
        .comment-text3 {
            font-size: 20px;
            margin-left: 100px;
            margin-top: -20px !important;
            
        }
        .comment-text4 {
            font-size: 20px;
            margin-left: 100px;
            margin-top: -20px !important;
            width: 650px;
            height: 90px;
            word-wrap: break-word !important;
            overflow: hidden !important;
            text-overflow:  ellipsis !important; 
            white-space: normal !important;
        }

        .comimg
        {
            margin-left: ;
            margin-top: -20px;
        }
        /* CSS for rate text */
        .rate-text {
            font-size: 30px;
            margin-top: -150px;
            margin-left: 1180px;
        }

        /* CSS for ratestar div */
        .ratestar {
            margin-left: 1125px;
        }

        /* CSS for expanded image */
        #expandedImg {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            text-align: center;
        }

        /* CSS for expanded image img */
        #expandedImg img {
            max-width: 90%;
            max-height: 90%;
            margin-top: 5%;
            border: 1px solid #fff;
            border-radius: 20px;
        }

        .statusbtn
        {
            margin-left: 1280px;
            margin-top: -70px;
        }

        .hidebtn
        {
            width: 100px;
            height: 35px;
            background-color: #4CAF50;
            border-radius: 10px;
            color: white;
            margin-top:50px;
        }
        .deletebtn
        {
            width: 100px;
            height: 35px;
            background-color: #f44336;
            border-radius: 10px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="rate">
        <div class="pan">
            <p style="font-size: 28px !important;font-weight: bold !important;">Comment</p>
            <div class="filterratestar">
                <input type="checkbox" class="star-checkbox" value="1"> 1 Star
                <input type="checkbox" class="star-checkbox" value="2"> 2 Stars
                <input type="checkbox" class="star-checkbox" value="3"> 3 Stars
                <input type="checkbox" class="star-checkbox" value="4"> 4 Stars
                <input type="checkbox" class="star-checkbox" value="5"> 5 Stars
                <button onclick="handleStarFilter()">Apply Filter</button>
            </div>
        </div>



    </div>

        <?php
        while ($row = $qry->fetch_assoc()) :
        ?>
        <div class="comment">
            <img  class="user-img">
            <p class="comment-text1">ID : <?php echo $row['user_id'] ?></p>
            <p class="comment-text2">User: <?php echo $row['user_name'] ?></p>

            <p class="comment-text3">Date : <?php echo $row['datentime'] ?></p>
            <p class="comment-text4">Description comment : <?php echo $row['description'] ?></p>

            <!-- Original Image -->
            <div class="comimg">
                <img src="../assets/img/<?php echo $row['img_comment'] ?>" style="height: 150px;width: 200px;margin-left: 900px;margin-top: -165px; border: 1px solid; border-radius: 20px;" onclick="expandImage(this)">
            </div>
            <!-- Rate Text -->
            <p class="rate-text"><?php echo $row['rate'] ?></p>
            
            <!-- Rate Stars -->
            <div class="ratestar">
                <?php
                $rate = $row['rate'];
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rate) {
                        echo '<img src=".\assets\img\star.png" style="height: 25px;width: 25px;" id="r'.$i.'">';
                    } else {
                        echo '<img src=".\assets\img\star_empty.png" style="height: 25px;width: 25px;" id="r'.$i.'">';
                    }
                }
                ?>
            </div>

            <!-----button status for hide and delete comment---->
            <div class="statusbtn">
                <?php if ($row['status_comment'] == 1): ?>
                    <button class="hidebtn" onclick="toggleCommentVisibility(<?php echo $row['rate_id']; ?>, 'hide')">Hide</button>
                <?php else: ?>
                    <button class="hidebtn" onclick="toggleCommentVisibility(<?php echo $row['rate_id']; ?>, 'show')">Show</button>
                <?php endif; ?>
            </div>

        </div>
        <?php endwhile; ?>
    </div>

    <div id="expandedImg" onclick="closeImage()">
        <span style="color: #fff; cursor: pointer; position: absolute; top: 10px; right: 20px; font-size: 24px;">&times;</span>
        <img src="">
    </div>

    <script>
        // Function to expand the image
        function expandImage(img) {
            var expandedImg = document.getElementById("expandedImg");
            var imgSrc = img.src;
            expandedImg.style.display = "block";
            expandedImg.innerHTML = '<span style="color: #fff; cursor: pointer; position: absolute; top: 10px; right: 20px; font-size: 24px;" onclick="closeImage()">&times;</span><img src="' + imgSrc + '">';
        }

        // Function to close the expanded image
        function closeImage() {
            var expandedImg = document.getElementById("expandedImg");
            expandedImg.style.display = "none";
        }

        // Function to handle star checkbox changes
        function handleStarCheckboxChange(checkbox) {
            var currentUrl = window.location.href;
            var urlWithoutRating = currentUrl.replace(/([&?]selected_rating=)[^&]*/i, ''); // Remove existing rating parameter
            var selectedRatings = document.querySelectorAll('.star-checkbox:checked');
            var ratingsArray = Array.from(selectedRatings).map(function(checkbox) {
                return checkbox.value;
            });
            var ratingQueryString = ratingsArray.length > 0 ? 'selected_rating=' + ratingsArray.join(',') : '';
            var newUrl;
            if (urlWithoutRating.includes('?')) {
                newUrl = urlWithoutRating + '&' + ratingQueryString;
            } else {
                newUrl = urlWithoutRating + '?' + ratingQueryString;
            }
            window.location.href = newUrl;
        }
        // Function to handle star filter button click
        function handleStarFilter() {
            handleStarCheckboxChange();
        }


        // Function to handle hiding comment
        function hideComment(rateId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Update UI or handle success message
                        console.log(xhr.responseText);
                        // For demonstration, you can hide the comment element
                        document.getElementById('comment_' + rateId).style.display = 'none';
                    } else {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.open("POST", "update_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("rate_id=" + rateId);
        }

        function toggleCommentVisibility(rateId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Toggle button text based on the response
                        var button = document.querySelector('#comment_' + rateId + ' .hidebtn');
                        if (xhr.responseText === 'Status updated successfully') {
                            var currentText = button.innerText;
                            button.innerText = currentText === 'Hide' ? 'Show' : 'Hide';
                        }
                    } else {
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.open("POST", "update_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("rate_id=" + rateId);
            window.location.reload();

        }


        
        function deleteComment(rateId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Optionally, update the UI or provide feedback to the user
                        console.log(xhr.responseText);
                        // Reload the page or update the comment section
                        location.reload(); // Reload the page to reflect the deleted comment
                    } else {
                        console.error(xhr.responseText);
                        // Handle the error appropriately
                    }
                }
            };
            xhr.open("POST", "delete_comment.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("rate_id=" + rateId);
            window.location.reload();

        }



    </script>
</body>
</html>
