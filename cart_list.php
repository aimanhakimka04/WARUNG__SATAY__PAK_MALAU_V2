<div style="margin-top:100px;"></div>
<section class="page-section" id="menu">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="sticky">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8"><b>Items</b>
                                    <input type="hidden" id="feedelivery" name="feedelivery" value="">
                                </div>
                                <div class="col-md-4 text-right"><b>Total</b></div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                if (isset($_SESSION['login_user_id'])) {
                    $data = "where c.user_id = '" . $_SESSION['login_user_id'] . "' ";
                } else {
                    $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
                    $data = "where c.client_ip = '" . $ip . "' ";
                }
                $total = 0;

                $get = $conn->query("SELECT *,c.id as cid FROM cart c inner join product_list p on p.id = c.product_id " . $data);
                while ($row = $get->fetch_assoc()) :
                    $total += ($row['qty'] * $row['price']);
                ?>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center" style="text-align: -webkit-center ">
                                <div class="col-auto">
                                    <a href="admin/ajax.php?action=delete_cart&id=<?php echo $row['cid'] ?>" class="rem_cart btn btn-sm btn-outline-danger" data-id="<?php echo $row['cid'] ?>"><i class="fa fa-trash"></i></a>
                                </div>
                                <div class="col-auto flex-shrink-1 flex-grow-1 text-center">
                                    <img src="assets/img/<?php echo $row['img_path'] ?>" alt="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p><b>
                                        <large> <?php echo $row['name'] ?></large>
                                    </b></p>
                                <p class='truncate'> <b><small><?php echo $row['description'] ?></small></b></p>
                                <p> <b><small>Unit Price :RM <?php echo number_format($row['price'], 2) ?></small></b></p>
                                <p><small>Stock: <?php echo $row['quantity'] ?></small></p>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary qty-minus" type="button" data-id="<?php echo $row['cid'] ?>"><span class="fa fa-minus"></button>
                                    </div>
                                    <input type="number" readonly value="<?php echo $row['qty'] ?>" min=0 max="<?php echo $row['quantity'] ?>" class="form-control text-center" name="qty">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary qty-plus" type="button" id="" data-id="<?php echo $row['cid'] ?>"><span class="fa fa-plus"></span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <b>
                                    <large>RM<?php echo number_format($row['qty'] * $row['price'], 2) ?></large>
                                </b>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>
            </div>

            <div class="col-md-4">
                <div class="sticky">
                    <div class="card">
                        <div class="card-body">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title">Delivery To / Pickup To</h5>
                                    <div class="form-group">
                                        <label for="deliveryPickupLocation">Location:</label>
                                        <input type="text" class="form-control" id="deliveryPickupLocation" placeholder="Seletion delivery or pickup location" readonly>
                                        <div style="width: 30px; height: 40px;">
                                            <a class="choossloc" id="delivery-click">
                                                <img src="images/arrowDown-vr7.svg" style="width:25px; height:25px;">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="dateTime">Date and Time:</label>
                                        <input type="text" class="form-control" id="dateTime" placeholder="Select date time delivery or pickup location" readonly>
                                        <div style="width: 30px; height: 40px;">
                                            <a class="choosetime" id="date-click">
                                                <img src="images/arrowDown-vr7.svg" style="width:25px; height:25px;">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <p>
                                <large>Total Amount</large>
                            </p>
                            <hr>
                            <?php
                            $_SESSION['subtotal'] = $total + ($total * 0.08);
                            $_SESSION['service_tax'] = ($total) * 0.06;
                            $_SESSION['total'] = ($_SESSION['subtotal'] + $_SESSION['service_tax']);
                            ?>
                            <p class="text-left"><b>Subtotal (incl. SST) <a style="float: right; margin-right: 10px;color: rgb(15, 101, 58);font-style: normal;"><b>RM<?php echo number_format($_SESSION['subtotal'], 2) ?></b></a></b></p>
                            <p class="text-left"><b>Service Tax (6%) <a style="float: right; margin-right: 10px;color: rgb(15, 101, 58);font-style: normal;"><b>RM<?php echo number_format($_SESSION['service_tax'], 2) ?></b></a></b></p>
                            <p class="text-left"><b>Total (incl. SST) <a style="float: right; margin-right: 10px;color: rgb(15, 101, 58);font-style: normal;"><b>RM<?php echo number_format($_SESSION['total'], 2) ?></b></a></b></p>

                            <hr>
                            <div class="text-center">
                                <button class="btn btn-block btn-outline-dark" type="button" id="checkout">Proceed to checkout </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .card p {
        margin: unset;
    }

    .card img {
        max-width: calc(100%);
        max-height: calc(59%);
    }

    div.sticky {
        position: -webkit-sticky;
        /* Safari */
        position: sticky;
        top: 4.7em;
        z-index: 10;
        background: white;
    }

    .rem_cart {
        position: absolute;
        left: 0;
    }
</style>

<script>
    $('.view_prod').click(function() {
        uni_modal_right('Product', 'view_prod.php?id=' + $(this).attr('data-id'))
    })
    $('.qty-minus').click(function() {
        var qtyInput = $(this).parent().siblings('input[name="qty"]');
        var qty = parseInt(qtyInput.val());
        var productId = $(this).attr('data-id');

        // Check if the current quantity is already 1
        if (qty > 1) {
            // Decrement the quantity by one
            qtyInput.val(qty - 1);
            // Call the function to update the quantity in the cart
            update_qty(qty - 1, productId);
        } else {
            // If the current quantity is already 1, do nothing or display a message to the user
            // For now, I'll just log a message to the console
            console.log("Quantity is already 1, cannot decrement further.");
        }
    });

    $('.qty-plus').click(function() {
        var qtyInput = $(this).parent().siblings('input[name="qty"]');
        var qty = parseInt(qtyInput.val());
        var maxQty = parseInt(qtyInput.attr('max')); // Assuming the maximum quantity is specified as the 'max' attribute

        // Check if the current quantity is less than the maximum quantity (stock)
        if (qty < maxQty) {
            // Increment the quantity by one
            qtyInput.val(qty + 1);
            // Call the function to update the quantity in the cart
            update_qty(qty + 1, $(this).attr('data-id'));
        } else {
            // Alert the user if there's not enough stock available
            alert('Not enough stock available');
        }
    });

    $('.choossloc').click(function() {
        uni_modal('', '../deliveryloc.php')
    })
    $('.choosetime').click(function() {
        uni_modal('', '../deliverydate.php')
    })


    function update_qty(qty, id) {
        start_load()
        $.ajax({
            url: 'admin/ajax.php?action=update_cart_qty',
            method: "POST",
            data: {
                id: id,
                qty
            },
            success: function(resp) {
                if (resp == 1) {
                    load_cart()
                    end_load()
                }
            }
        })
    }

    function load_cart() {
        $.ajax({
            url: 'admin/ajax.php?action=get_cart_count',
            method: "POST",
            success: function(resp) {
                if (resp) {
                    $('.item_count').html(resp)
                }
            }
        })
    }
    $('#checkout').click(function() {
        <?php
        $canCheckout = true;
        $problematicProducts = [];
        $get = $conn->query("SELECT *,c.id as cid FROM cart c inner join product_list p on p.id = c.product_id " . $data);
        while ($row = $get->fetch_assoc()) {
            if ($row['qty'] > $row['quantity']) {
                $canCheckout = false;
                $problematicProducts[] = $row['name'];
            }
        }
        ?>

        if ('<?php echo $canCheckout ?>' == '1') {
            if ('<?php echo isset($_SESSION['login_user_id']) ?>' == 1) {
                location.replace("index.php?page=checkout")
            } else {
                uni_modal("Checkout", "login.php?page=checkout")
            }
        } else {
            alert("The following products in your cart have a quantity greater than the available stock: " + '<?php echo implode(", ", $problematicProducts) ?>' + ". Please adjust your cart and try again.");
        }
    })

    window.onload = getmethodcollect;

    function getmethodcollect() {
        // Coordinates for the starting location
        var startCoords = [2.211194884155262, 102.25236063863798];

        // Check if "location" key exists in localStorage
        if (localStorage.getItem("location")) {

            var endlocationName = localStorage.getItem("location");

            document.getElementById("deliveryPickupLocation").value = endlocationName;

            // Get coordinates for the delivery location
            geocodeLocation(endlocationName, function(endCoords) {
                if (endCoords) {
                    // Calculate and display the distance
                    calculateDistance(startCoords, endCoords);
                } else {
                    document.getElementById("total_distance").innerHTML = 'Error: Unable to geocode the location';
                }
            });
        }

        if (localStorage.getItem("selectedTime")) {
            if (localStorage.getItem("selectedDate")) {
                var selectedTime = localStorage.getItem("selectedTime");

                var selectedDate = localStorage.getItem("selectedDate");
                document.getElementById("dateTime").value = selectedDate + " " + selectedTime;
            }
        }
    }

      

    // Function to geocode a location name using the Nominatim API
    function geocodeLocation(locationName, callback) {
        var url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(locationName)}&format=json&limit=1`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var coords = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                    callback(coords);
                } else {
                    callback(null);
                }
            })
            .catch(() => callback(null));
    }

    // Function to calculate distance using OSRM
    function calculateDistance(start, end) {
        var url = `https://router.project-osrm.org/route/v1/driving/${start[1]},${start[0]};${end[1]},${end[0]}?overview=false`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.code === 'Ok') {
                    var route = data.routes[0];
                    var distance = route.distance / 1000; // convert to kilometers
                    var delfee = distance * 0.4;
                    var roundedFee = Math.ceil(delfee);

                    // Save the delivery fee in local storage
                    localStorage.setItem("del_fee", roundedFee);
                    // Pass the rounded fee to PHP using AJAX
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "checkup_updatetotal.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Update UI or do something with the response from PHP if needed
                        }
                    };
                    xhr.send("roundedFee=" + roundedFee);
                } else {
                    document.getElementById("total_distance").innerHTML = 'Error: ' + data.message;
                }
            });
    }

    // Initialize the map (optional if you want to show the route)
    var map = L.map('map').setView([2.2135, 102.2501], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
</script>
