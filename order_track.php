<?php
include 'admin/db_connect.php';
$user_id = $_SESSION['login_user_id'];
$order_id = $_GET['id'];
$qrydelivery = $conn->query("SELECT o.*, u.user_id FROM orders o INNER JOIN user_info u ON o.email = u.email WHERE o.id = $order_id");
$qryRider = $conn->query("SELECT last_location FROM rider WHERE order_id = $order_id");

// Check if the query returned any result
$rider_location = null;
if ($qryRider && $qryRider->num_rows > 0) {
    $rider_location = $qryRider->fetch_assoc()['last_location'];
}

// Check if the rider's location is not null
if ($rider_location) {
    // Extract rider's latitude and longitude from the location string
    list($rider_lat, $rider_lng) = explode(',', $rider_location);
} else {
    // Set default coordinates or handle the null value appropriately
    $rider_lat = 0; // Default latitude
    $rider_lng = 0; // Default longitude
}

$order = $qrydelivery->fetch_assoc();
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<div style="margin-top:100px;"></div>
<?php 
    if($order['collect_method'] == "selfCollect")
    {
        echo '<div class="wrapper" style="margin-left:50%px;margin-right:50%;display:block;">';
    }
    else
    {
        echo '<div class="wrapper">';
    }
    ?>

    <button id="printpage" class="printpage" onclick="window.print()">Print Receipt</button>

    <div class="rec" style="">
        <img src=".\images\logo.png" style="height: 200px; width:200px ; border-radius: 50px; display: block;margin-left: auto;margin-right: auto;">

        <h1>Thank For Your Order!</h1>
        <br>
        <h2>Your order</h2>
        <p id="datet" style="text-align: center;">
        <p id="datet" style="text-align: center;"><?php echo date('Y-m-d H:i:s', strtotime($order['date'])); ?></p>
        </p>
        <hr style="  border-top: 1px solid rgba(201, 201, 201, 0.513);  ">
        <div class="order">
            <?php
            // Get the latest order ID from the database
            $query = "SELECT MAX(order_id) AS latest_order_id FROM order_list";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();
            $latest_order_id = $row['latest_order_id'];

            // Check if the latest order ID exists
            //if ($_SESSION['wallettopup'] == true) { 
            if (isset($_SESSION['wallettopup']) && $_SESSION['wallettopup'] == true) { ?>
                <p style="text-align: left;">E Wallet Topup</p>
                <p style="margin-left:280px; margin-top: -40px;">NA</p>
                <p style="text-align: right; margin-top: -40px;">RM<?php echo number_format($_SESSION['order_amount'], 2) ?></p>
                <hr style="border-top: 1px solid rgba(201, 201, 201, 0.513);">
                <?php $_SESSION['wallettopup'] = false ?>
                <?php } else if ($latest_order_id !== null) {
                // Use the latest order ID in your query
                $total = 0; // Initialize total outside the loop
                $qry = $conn->query("SELECT * FROM order_list o INNER JOIN product_list p ON o.product_id = p.id WHERE order_id =" . $order_id);
                while ($row = $qry->fetch_assoc()) : // Start of the loop
                    // Calculate total for each item
                    $itemTotal = $row['qty'] * $row['price'];
                    $total += $itemTotal; // Add to the total
                ?>
                    <!-- HTML to display order details -->
                    <div>
                        <p style="text-align: left;"><?php echo $row['name'] ?></p>
                        <p style="margin-left:280px; margin-top: -40px;"><?php echo $row['qty'] ?></p>
                        <p style="text-align: right; margin-top: -40px;">RM<?php echo number_format($itemTotal, 2) ?></p>
                        <hr style="border-top: 1px solid rgba(201, 201, 201, 0.513);">
                    </div>
                <?php endwhile; // End of the loop 
                ?>
            <?php
            } else {
                echo "No order found";
            }
            ?>
            <?php if (isset($_SESSION['wallettopup']) && $_SESSION['wallettopup'] != true) { ?>
                <div class="othersub">
                    <p style="text-align: left;">
                        Subtotal (incl. SST)
                    </p>
                    <p style="text-align: right; margin-top: -30px;">RM<?php echo number_format($_SESSION['subtotal'], 2) ?></p>
                    <hr style="  border-top: 1px solid rgba(201, 201, 201, 0.513);  ">
                    <p style="text-align: left;">
                        Service Tax (6%)
                    </p>
                    <p style="text-align: right; margin-top: -30px;">RM<?php echo number_format($_SESSION['service_tax'], 2) ?></p>

                </div>
                <hr style="  border-top: 4px solid rgba(0, 0, 0, 0.513);  ">
                <div class="total">
                    <p style="text-align: left;">
                        Total (incl. SST)
                    </p>
                    <p style="text-align: right; margin-top: -30px;">RM<?php echo number_format($_SESSION['total'], 2) ?></p>
                    <hr style="  border-top: 4px solid rgba(0, 0, 0, 0.513);  ">
                </div>
            <?php } ?>

            <h2>Your Details</h2>
            <p id="collect_method" style="text-align: left;">
                <?php echo $order['collect_method']; ?>
            </p>
            <p id="locationcollect" style="margin-left: 300px;  margin-top: -32px;word-wrap: break-word;width: 200px;">
                <?php
                // Assuming $order['address'] contains the coordinates in the format "latitude,longitude"
                $coordinates = $order['address'];

                // Predefined address for specific coordinates
                $predefined_coordinates = "2.211194884155262,102.25236063863798";
                $predefined_address = "Warung SATAY PAK MALAU, 75100 Malacca";

                // Check if the coordinates match the predefined coordinates
                if (trim($coordinates) === $predefined_coordinates) {
                    echo $predefined_address;
                } else {
                    // Function to convert coordinates to address using OpenStreetMap Nominatim API
                    function coordinatesToAddress($lat, $lng)
                    {
                        $curl = curl_init();

                        // Construct API endpoint URL
                        $api_url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lng";

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $api_url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_USERAGENT => 'YourAppName', // Set a user-agent header
                            CURLOPT_FOLLOWLOCATION => true, // Follow redirects
                            CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification (not recommended for production)
                        ));

                        $response = curl_exec($curl);
                        $curl_error = curl_error($curl);
                        curl_close($curl);

                        if ($curl_error) {
                            return 'Curl error: ' . $curl_error;
                        }

                        // Decode JSON response
                        $json = json_decode($response);

                        // Check HTTP status code
                        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        if ($http_status !== 200) {
                            return 'HTTP Error: ' . $http_status;
                        }

                        // Check if JSON decoding was successful
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            return 'Error decoding JSON: ' . json_last_error_msg();
                        }

                        // Check if the request was successful and display name is available
                        if (!empty($json->display_name)) {
                            return $json->display_name;
                        } else {
                            return 'Unable to retrieve address';
                        }
                    }

                    // Example usage:
                    $coordinates = $order['address']; // Assuming $order['address'] contains "latitude,longitude"
                    list($lat, $lng) = explode(',', $coordinates);
                    $address = coordinatesToAddress($lat, $lng);
                    echo "$address";
                }
                ?>

            </p>



            <p id="subtotal" style="text-align: left;">Subtotal</p>
            <p id="psubtotal;" style="margin-left: 300px;  margin-top: -30px;word-wrap: break-word;width: 200px;"> RM <?php echo number_format(($total * 0.08) + $total, 2) ?>
            </p>

            <p id="delfee" style="text-align: left;"></p>
            <p id="pdelfee" style="margin-left: 300px;  margin-top: -30px;word-wrap: break-word;width: 200px;">

            </p>
            <p id="sst" style="text-align: left; margin-top:35px;">Service Tax</p>
            <p id="psst" style="margin-left: 300px;  margin-top: -30px;word-wrap: break-word;width: 200px;">RM


                <?php echo number_format($total * 0.06, 2) ?>

            </p>
            <p id="tot" style="text-align: left;">Total</p>
            <p id="ptot" style="margin-left: 300px;  margin-top: -30px;word-wrap: break-word;width: 200px;">RM

                <?php echo number_format($order['total_fee'], 2) ?></b>
            </p>



            <hr style="  border-top: 1px solid rgba(201, 201, 201, 0.513);  ">


            <p style="text-align: left;">
                Billed to</p>
            <p style="margin-left: 300px;  margin-top: -30px;word-wrap: break-word;width: 200px;">
                <?php
                echo $order['payment_method'];

                ?>
            </p>

            <hr style="  border-top: 1px solid rgba(201, 201, 201, 0.513);  ">

            <br>
            <p style="text-align: center;">
                Notice something wrong? Contact us at <a href="https://wa.me/0175019310">0175019310</a> and we'll be happy to help.
            </p>


            <button type="button" id="back" style="background-color: rgb(0, 102, 255);color: white; width: 150px;height: 70px;font-weight: bold;border-radius: 5px;display: block;margin-left: auto;margin-right: auto;margin-top:30px ;">View My Account</button>
            <br>
            <p style="text-align: center;">Thanks for being a great customer</p>

        </div>
    </div>

    <?php 
    if($order['collect_method'] == "selfCollect")
    {
        echo '<div class="containerdelivery" style="display:none;">';
    }
    else
    {
        echo '<div class="containerdelivery">';
    }
    ?>
        <h1>Delivery Tracking</h1>
        <div class="progress-wrapper">
            <div class="progress-stage">
                <img src="images/verify.png" alt="Verification" class="icon">
                <div class="progress-container">
                    <div class="progress-bar" id="verification-bar"></div>
                </div>
                <span>Verification</span>
            </div>
            <div class="progress-stage">
                <img src="images/trolley.png" alt="Preparing" class="icon">
                <div class="progress-container">
                    <div class="progress-bar" id="preparing-bar"></div>
                </div>
                <span>Preparing</span>
            </div>
            <div class="progress-stage">
                <img src="images/deliverfood.png" alt="Delivering" class="icon">
                <div class="progress-container">
                    <div class="progress-bar" id="delivering-bar"></div>
                </div>
                <span>Delivering</span>
            </div>
            <div class="progress-stage">
                <img src="images/housegarden.png" alt="Complete" class="icon">
                <div class="progress-container">
                    <div class="progress-bar" id="complete-bar"></div>
                </div>
                <span>Complete</span>
            </div>
        </div>
        <div id="time-remaining">Time remaining: <span id="time"></span></div>
        <div id="placeholder">
            <img src="images/animationdelivery.gif" alt="Placeholder" id="placeholder-image">
        </div>
        <div class="custom-rating-container" id="rateus" style="display:none;">
            <h1>Rate Us</h1>
            <div class="custom-stars" id="custom-stars" required>
                <img src="admin/assets/img/star_empty.png" alt="Star" data-value="1">
                <img src="admin/assets/img/star_empty.png" alt="Star" data-value="2">
                <img src="admin/assets/img/star_empty.png" alt="Star" data-value="3">
                <img src="admin/assets/img/star_empty.png" alt="Star" data-value="4">
                <img src="admin/assets/img/star_empty.png" alt="Star" data-value="5">
            </div>
            <p id="custom-rating-value">Rating: </p>
            <form class="custom-rating-form" id="custom-ratingForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="custom-rating" id="custom-rating" value="">
                <textarea id="custom-description" name="custom-description" placeholder="Write your review..." required></textarea>
                <input type="file" name="custom-img_comment" id="custom-img_comment" accept="image/png, image/jpeg, image/gif" required>
                <img id="custom-img-preview" src="#" alt="Image Preview" style="display:none;">
                <button type="submit">Submit</button>
            </form>
        </div>


        <div class="comments-container" id="comments-section" style="display:none;">
        <?php
            // Database connection assumed to be established in $conn
            $limit = 999;
            $page = (isset($_GET['_page']) && $_GET['_page'] > 0) ? $_GET['_page'] - 1 : 0;
            $offset = $page > 0 ? $page * $limit : 0;

            // Assuming $order_id is defined and sanitized

            // Query to count total comments for a specific order_id
            $all_comments_count_query = "SELECT rate_id FROM rating WHERE status_comment = 2 AND order_id = ?";
            $stmt = $conn->prepare($all_comments_count_query);
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $all_comments_count = $result->num_rows;
            $page_btn_count = ceil($all_comments_count / $limit);

            // Query to fetch comments based on the selected date (if any), rating, pagination, and specific order_id
            $comments_query = "SELECT * FROM rating WHERE status_comment = 2 AND order_id = ? ORDER BY user_id ASC LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($comments_query);
            $stmt->bind_param("iii", $order_id, $limit, $offset);
            $stmt->execute();
            $qry = $stmt->get_result();

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
        <?php endwhile; ?>
    </div>



        <button id="confirm" data-id="<?php echo $order['id'] ?>" style="display:none; margin-left:35%;width:174.44px;">Confirm</button>
        <button class="viewproofbtn" id="btnprof" data-id="<?php echo $order['id'] ?>" style="margin-left:35%;display:none;">View Order Proof</button>

        <div id="map" style="display:none;"></div>
    </div>
</div>



<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="script.js"></script>




<style>
    /* General styling for the wrapper */
    .wrapper {
        display: flex;
        justify-content: space-between;
    }

    /*FOR RECIEPT */
    .logo {
        height: 150px;
        width: 150px;
        border-radius: 50%;
        margin-bottom: 20px;
    }

    h1,
    h2 {
        font-weight: 100;
        text-align: center;
    }

    hr {
        border-top: 1px solid rgba(201, 201, 201, 0.513);
        margin: 20px 0;
    }

    .order-details {
        text-align: left;
        margin-bottom: 30px;
    }

    .order-total {
        text-align: right;
        margin-top: -30px;
    }

    .contact-info {
        margin-top: 50px;
    }

    .social-icons {
        margin-top: 50px;
    }

    .social-icons img {
        height: 50px;
        width: 50px;
        border-radius: 50%;
        margin: 0 10px;
        transition: transform 0.3s ease-in-out;
    }

    .social-icons img:hover {
        transform: scale(1.1);
    }

    .imgshare {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .imgshare a {
        margin: 0 10px;
        /* Adjust spacing between icons */
        /
    }

    .printpage {
        position: fixed;
        bottom: 20px;
        left: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        transform: translateY(-250%);
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        opacity: 0.5;
    }

    .printpage:hover {
        background-color: #0056b3;
        opacity: 0.5;
    }




    /*END OF RECIEPT */
    /* Styling for the containerdelivery class */
    .containerdelivery {
        width: 60%;
        height: 100%;
        padding: 20px;
    }

    /* Styling for the rec class */
    .rec {
        padding: 20px;
        border-left: 1px solid #ccc;
        width: 700px;
        height: 100%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        border-radius: 8px;
        margin-left: auto;
        margin-right: -30px;
        margin-top: 29px;
        margin-bottom: 29px;
    }

    /* Add margins and padding to the elements if necessary */
    .rec img {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .containerdelivery {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 600px;
        text-align: center;
        margin: 0 auto;
        margin-top: 29px;
        margin-bottom: 29px;
        margin-left: 100px;

    }

    .progress-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .progress-stage {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 23%;
    }

    .icon {
        width: 30px;
        height: 30px;
        margin-bottom: 5px;
    }

    .progress-container {
        background-color: #e0e0e0;
        border-radius: 25px;
        position: relative;
        height: 10px;
        width: 100%;
        margin-bottom: 5px;
    }

    .progress-bar {
        background-color: #76c7c0;
        height: 100%;
        border-radius: 25px;
        width: 0;
        transition: width 0.4s ease;
    }

    #map,
    #placeholder {
        width: 100%;
        height: 300px;
        margin-top: 20px;
    }

    #placeholder-image {
        width: 300px;
        height: 100%;
        object-fit: cover;
    }

    #time-remaining {
        font-size: 1.2em;
        margin-bottom: 20px;
    }

    button {
        padding: 10px 20px;
        margin-top: 20px;
        font-size: 16px;
        background-color: #76c7c0;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #5daaa5;
    }

    button+button {
        margin-left: 10px;
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

    .modal-footer {
        display: none;
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
        max-width: 1000px;
        /* Optional: Set a maximum width to prevent it from becoming too large on wide screens */
        margin-left: 25%;

        /* Centering the container */
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }




    .comment {
        width: 300px;
        height: auto;
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

    .comment img {
        width: 230px;
        height: 170px;
    }

    .comment img.comimg {
        cursor: pointer;
        border-radius: 10px;
        width: 230px;
        height: 170px;
        object-fit: cover;
        /* Ensures the image covers the entire area without distortion */

    }

    .ratestar img {
        width: 25px;
        height: 25px;
    }
</style>


<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<script>
    // script.js
    var map;
    var riderMarker;
    var customerLatLng = [2.2519961, 102.2878325];
    var riderLatLng = [<?php echo $rider_lat; ?>, <?php echo $rider_lng; ?>]; // initial rider location
    var deliveryTime = 0; // Global variable to store delivery time

    function initMap() {
    map = L.map('map').setView(customerLatLng, 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add marker for the customer's location
    L.marker(customerLatLng).addTo(map).bindPopup('Customer Location').openPopup();

    // Create a custom motorcycle icon
    var motorcycleIcon = L.icon({
        iconUrl: '../images/Scooter.png',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });

    // Add marker for the rider's location with the custom icon
    riderMarker = L.marker(riderLatLng, {
        icon: motorcycleIcon
    }).addTo(map).bindPopup('Rider Location').openPopup();

    // Use Leaflet Routing Machine to create a route from the rider's location to the customer's location
    var control = L.Routing.control({
        waypoints: [
            L.latLng(riderLatLng[0], riderLatLng[1]),
            L.latLng(customerLatLng[0], customerLatLng[1])
        ],
        createMarker: function(i, waypoint, n) {
            if (i === 0) {
                return L.marker(waypoint.latLng, {
                    icon: motorcycleIcon
                }).bindPopup('Rider Location').openPopup();
            } else {
                return L.marker(waypoint.latLng).bindPopup('Customer Location').openPopup();
            }
        },
        routeWhileDragging: false,
        addWaypoints: false,
        show: false // Suppresses the turn-by-turn instructions
    }).addTo(map);

    control.on('routesfound', function(e) {
        var routes = e.routes;
        var summary = routes[0].summary;
        deliveryTime = summary.totalTime; // Total time in seconds

        // Display the initial ETA in the #time element
        document.getElementById('time').innerText = formatTime(deliveryTime);

        // Update the stages array with the new deliveryTime
        stages[2].duration = deliveryTime;
    });
}
    function updateRiderLocation() {
    var orderId = <?php echo $order_id; ?>;
    fetch('get_rider_location.php?id=' + orderId)
        .then(response => response.json())
        .then(data => {
            var newLocation = data.location.split(',');
            var newLat = parseFloat(newLocation[0]);
            var newLng = parseFloat(newLocation[1]);

            // Update the rider's marker position
            riderMarker.setLatLng([newLat, newLng]);

            // Update the routing control with new waypoints
            var control = L.Routing.control({
                waypoints: [
                    L.latLng(newLat, newLng),
                    L.latLng(customerLatLng[0], customerLatLng[1])
                ],
                createMarker: function(i, waypoint, n) {
                    if (i === 0) {
                        return L.marker(waypoint.latLng, {
                            icon: motorcycleIcon
                        }).bindPopup('Rider Location').openPopup();
                    } else {
                        return L.marker(waypoint.latLng).bindPopup('Customer Location').openPopup();
                    }
                },
                routeWhileDragging: false,
                addWaypoints: false,
                show: false // Suppresses the turn-by-turn instructions
            }).addTo(map);

            control.on('routesfound', function(e) {
                var routes = e.routes;
                var summary = routes[0].summary;
                deliveryTime = summary.totalTime; // Total time in seconds

                // Display the new ETA in the #time element
                document.getElementById('time').innerText = formatTime(deliveryTime);

                // Update the stages array with the new deliveryTime
                stages[2].duration = deliveryTime;
            });
        })
        .catch(error => console.error('Error fetching rider location:', error));
}

    // Function to format the time from seconds to minutes and seconds
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${minutes}m ${secs}s`;
    }

    // Function to handle the delivery order stage
    function deliveryorder() {
        preparingCompleted = true;
        document.getElementById('preparing-bar').style.width = '100%';

        // Update the delivery time in the #time element
        document.getElementById('time').innerText = formatTime(deliveryTime);

        verificationmarks();
        checkAndStartNextStage();
    }

    // Function to initialize the map and start updating the rider's location


    let stages = [{
            id: 'verification-bar',
            duration: 0
        },
        {
            id: 'preparing-bar',
            duration: 0
        },
        {
            id: 'delivering-bar',
            duration: deliveryTime // This will be updated dynamically
        },
        {
            id: 'complete-bar',
            duration: 0
        }
    ];

    let stageIndex = 0;
    let width = 0;
    let interval;

    function updateProgress() {
        if (stageIndex >= stages.length) {
            clearInterval(interval);
            return;
        }

        const stage = stages[stageIndex];
        const duration = stage.duration;

        width += (100 / duration);

        if (width >= 100) {
            document.getElementById(stage.id).style.width = '100%';
            width = 0;
            stageIndex++;
            if (stageIndex < stages.length) {
                setTimeout(updateProgress, 1000);
            }
        } else {
            document.getElementById(stage.id).style.width = `${width}%`;
            document.getElementById('time').innerText = formatTime(duration * (100 - width) / 100);
            setTimeout(updateProgress, 1000);
        }
    }

    function verificationmarks() {
        verificationCompleted = true;
        document.getElementById('verification-bar').style.width = '100%';
        checkAndStartNextStage();
    }

    function waitingdelivery() {
        verificationCompleted = true;
        document.getElementById('verification-bar').style.width = '100%';
        document.getElementById('preparing-bar').style.width = '100%';
        document.getElementById('placeholder').style.display = 'block';
        // Change the src of the image to the gif
        document.getElementById('placeholder-image').src = 'images/indelivery.gif';
        document.getElementById('map').style.display = 'none';
    }

    function checkAndStartNextStage() {
        if (verificationCompleted && preparingCompleted) {
            stageIndex = 2; // Move to the Delivering stage
            width = 0;
            interval = setTimeout(updateProgress, 1000);

            document.getElementById('placeholder').style.display = 'none';
            document.getElementById('map').style.display = 'block';
            initMap();
        }
    }

    function completeDelivery() {
        clearInterval(interval); // Stop the interval
        verificationCompleted = true;
        document.getElementById('verification-bar').style.width = '100%';
        preparingCompleted = true;
        document.getElementById('preparing-bar').style.width = '100%';
        document.getElementById('delivering-bar').style.width = '100%';
        document.getElementById('time').innerText = '0m 0s';



        document.getElementById('placeholder').style.display = 'none';
        document.getElementById('btnprof').style.display = 'block';
        document.getElementById('confirm').style.display = 'block';



    }

    

    function completeorder() {
        clearInterval(interval); // Stop the interval
        verificationCompleted = true;
        document.getElementById('verification-bar').style.width = '100%';
        preparingCompleted = true;
        document.getElementById('preparing-bar').style.width = '100%';
        document.getElementById('delivering-bar').style.width = '100%';
        document.getElementById('complete-bar').style.width = '100%';
        document.getElementById('time-remaining').style.display = 'none';



        document.getElementById('placeholder').style.display = 'none';
        document.getElementById('btnprof').style.display = 'block';
        document.getElementById('confirm').style.display = 'none';

          // Check if there's already a comment for the order
        <?php
        $order_id = $_GET['id'];
        $commentExists = $conn->query("SELECT rate_id FROM rating WHERE order_id = $order_id")->num_rows > 0;
        if ($commentExists):
        ?>
        document.getElementById('comments-section').style.display = 'block';
        <?php else: ?>
        document.getElementById('rateus').style.display = 'block';
        <?php endif; ?>
    }

    $('.viewproofbtn').click(function() {
        uni_modal('', '../proofod.php?id=' + $(this).attr('data-id'))
    })
   



    // Initialize the timer display but don't start the interval
    document.getElementById('time').innerText = formatTime(stages[2].duration);

    document.getElementById("back").onclick = function() {
        location.href = "index.php?page=home";
    };

    function resetProgress() {
        // Clear the interval if it's running
        if (interval) {
            clearInterval(interval);
        }

        // Reset variables
        stageIndex = 0;
        width = 0;
        verificationCompleted = false;
        preparingCompleted = false;

        // Reset progress bars
        stages.forEach(stage => {
            document.getElementById(stage.id).style.width = '0%';
        });

        // Hide map and show placeholder if necessary
        document.getElementById('placeholder').style.display = 'block';
        document.getElementById('map').style.display = 'none';

        // Reset the timer display
        document.getElementById('time').innerText = formatTime(stages[2].duration);
    }

    // Function to handle the confirm button click
    document.getElementById('confirm').addEventListener('click', function() {
        var orderId = this.getAttribute('data-id');
        // AJAX request to update order status
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_order_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle response from server if needed
                console.log(xhr.responseText);
                window.location.reload();

            }
        };
        xhr.send('order_id=' + orderId);
    });

    const stars = document.querySelectorAll('.custom-stars img');
const ratingValue = document.getElementById('custom-rating-value');
const ratingInput = document.getElementById('custom-rating');
const ratingForm = document.getElementById('custom-ratingForm');
const imgComment = document.getElementById('custom-img_comment');
const imgPreview = document.getElementById('custom-img-preview');
let currentRating = 0;

stars.forEach(star => {
    star.addEventListener('click', function () {
        currentRating = parseInt(this.getAttribute('data-value'));
        ratingInput.value = currentRating; // Update rating value
        updateStars(currentRating);
        ratingValue.textContent = `Rating: ${currentRating}`;
    });

    star.addEventListener('mouseover', function () {
        const hoverRating = parseInt(this.getAttribute('data-value'));
        updateStars(hoverRating);
    });

    star.addEventListener('mouseout', function () {
        updateStars(currentRating);
    });
});

function updateStars(rating) {
    stars.forEach((star, index) => {
        if (index < rating) {
            star.src = 'admin/assets/img/star.png';
        } else {
            star.src = 'admin/assets/img/star_empty.png';
        }
    });
}

imgComment.addEventListener('change', function () {
    const file = imgComment.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
//i want sent the order id to save_rate.php



ratingForm.addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Check if the rating has been set
    if (ratingInput.value === '') {
        alert('Please rate by clicking on a star before submitting the form.');
        return false;
    }

    const formData = new FormData(ratingForm);
    formData.append('order_id', <?php echo $order_id; ?>); // Add order ID to the form data
    formData.append('user_id', '<?php echo $user_id; ?>'); // Add user ID to the form data

    fetch('save_rate.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) 
    .then(data => {
        alert(data); // Show response from PHP script
        // Check the response in the console for debugging
        console.log(data);
        window.location.reload(); // Reload the page after submitting the form
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

</script>


<?php

$order_status = $order['status'];

$qrydelivery = $conn->query("SELECT * FROM rider WHERE order_id = $order_id");
$qrydelivery = $qrydelivery->fetch_assoc();
if ($order['status'] == 3 || $order['status'] == 4 || $order['status'] == 5){
    $deliverystatus = $qrydelivery['status_delivery'];
}


//0. waiting for verification
//1. verified
//2. cancelled
//3. ready for delivery
//4. delivered
//5. completed


if ($order_status == 0) {
    echo '<script>resetProgress();</script><head>
  <meta http-equiv="refresh" content="5"> 
</head>';
} else if ($order_status == 1) {
    echo '<script>verificationmarks();</script> <head>
  <meta http-equiv="refresh" content="5"> 
</head>';
} else if ($order_status == 2) {
} else if ($order_status == 3 && $deliverystatus == 0) {
    echo '<script>waitingdelivery();</script> <head>
  <meta http-equiv="refresh" content="5"> 
</head>';
} else if ($order_status == 3 && $deliverystatus == 1) {
    echo '<script>deliveryorder();
    //refresh every 2 second
   </script> <head>
  <meta http-equiv="refresh" content="5"> 
</head>';
} else if ($order_status == 4) {
    echo "<script>completeDelivery();</script>";
} else if ($order_status == 5) {
    echo "<script>completeorder();</script>";
}

?>