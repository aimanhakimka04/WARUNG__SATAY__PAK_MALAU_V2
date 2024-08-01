<?php
include 'admin/db_connect.php';
// var_dump($_SESSION); 
$chk = $conn->query("SELECT * FROM cart where user_id = {$_SESSION['login_user_id']} ")->num_rows;
if ($chk <= 0) {
    echo "<script>alert('You don\'t have an Item in your cart yet.'); location.replace('./')</script>";
}

//check the discound point of the user
$discount_point = $conn->query("SELECT * FROM user_info where user_id = {$_SESSION['login_user_id']} ")->fetch_array()['discount_point'];
?>


<script>
  

  function getmethodcollect() {
 // Coordinates for the starting location
    var startCoords = [2.211194884155262, 102.25236063863798];

        // Check if "location" key exists in localStorage
    if (localStorage.getItem("location")) {
        // If "location" exists, set innerHTML to "Delivery"
        document.getElementById("delfeetax").style.display = "block";

        document.getElementById("collect_method").innerHTML = "Delivery";
        document.getElementById("collect_methodname").innerHTML = "Delivery";
        document.getElementById("collect_method").setAttribute("value", "delivery");

        document.getElementById("locationcollect").innerHTML = localStorage.getItem("location");
        var endlocationName = localStorage.getItem("location");
        // Get coordinates for the delivery location
        geocodeLocation(endlocationName, function(endCoords) { // Callback function to handle the response from geocodeLocation
                    if (endCoords) {

                        var latitude = endCoords[0];
                        var longitude = endCoords[1];
                        var coordsString = latitude + "," + longitude;
                        document.getElementById("address").setAttribute("value", coordsString);


                        // Calculate and display the distance
                        calculateDistance(startCoords, endCoords);
                    } else {
                        document.getElementById("total_distance").innerHTML = 'Error: Unable to geocode the location';
                    }
                });


    } else {
        // If "location" doesn't exist, set innerHTML to "Self Collect"
        document.getElementById("delfeetax").style.display = "none";

        document.getElementById("collect_method").innerHTML = "Self Collect";
        document.getElementById("collect_methodname").innerHTML = "Self Collect";

        document.getElementById("collect_method").setAttribute("value", "selfCollect");
        document.getElementById("locationcollect").innerHTML = "Warung SATAY PAK MALAU, 75100 Malacca";
        document.getElementById("address").setAttribute("value", "2.211194884155262,102.25236063863798");
    }
}


window.onload = function() {
    getmethodcollect();
    updateDateTime();
    calculateDistance(start, end);

    // Call a function to reload the page after 1 second
    setTimeout(function() {
        reloadPage();
    }, 1000);
};


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
                document.getElementById("total_distance").innerHTML = 'Total distance: ' + distance.toFixed(2) + ' km';
                var delfee = distance * 0.4;
                var roundedFee = Math.ceil(delfee);

                // Set the rounded delivery fee as the content of the <p> element
                document.getElementById("delfee").textContent = roundedFee.toFixed(2);
                document.getElementById("feedelivery").setAttribute("value", roundedFee);

                

               




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

                    // Set the flag to true to indicate the request has been sent
                   

                // Set the rounded delivery fee as a custom attribute value
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



    function updateDateTime() {
                if (localStorage.getItem("selectedDate") && localStorage.getItem("selectedTime")) {
                    // If selectedDate and selectedTime exist in localStorage, set the date-time element's innerHTML
                    document.getElementById("date-time").innerHTML = localStorage.getItem("selectedDate") + ' ' + localStorage.getItem("selectedTime");
                    if (localStorage.getItem("selectedDate") && localStorage.getItem("selectedTime")) {
                        let date = localStorage.getItem("selectedDate");
                        let time = localStorage.getItem("selectedTime");

                        // Create a new XMLHttpRequest object
                        let xhr = new XMLHttpRequest();

                        // Define the request type, URL, and asynchronous flag
                        xhr.open("POST", "cdatetimesave.php", true);

                        // Set the request header
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                        // Define a function to handle the response
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                if (xhr.status === 200) {
                                    // Parse the JSON response
                                    var response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        // Handle success
                                        alert(response.message);
                                    } else {
                                        // Handle failure
                                        alert(response.message);
                                    }
                                } else {
                                    // Handle errors here
                                    console.error('XHR request failed:', xhr.statusText);
                                }
                            }
                        };


                        // Construct the query string with selectedDate and selectedTime
                        let datet = "selectedDate=" + date;
                        let timet = "selectedTime=" + time;

                        // Send the request with the selectedDate and selectedTime query strings
                        xhr.send(datet + "&" + timet);
                    }

                } else {
                    // If selectedDate and selectedTime don't exist in localStorage, set the date-time element's innerHTML to "Not selected"
                    alert("Please select a date and time for delivery");
                    document.getElementById("date-time").innerHTML = 'Not selected';
                    window.location.href = "index.php?page=home";
                }
            }

            document.addEventListener('DOMContentLoaded', (event) => {
            });


          




   $(document).ready(function () {

    
       

/*
        //cash
        $('#cash').change(function () {
            if ($(this).is(':checked')) {
                $('#paymentMethodContainer').html(`
                <div id="paymentMethodContainer" style="text-align: center;">
                <button id="checkout-cas" type="submit" style="width: 90%; height: 30px; background-color: rgb(202, 202, 202); color: white; border-radius: 20px;">Proceed with Cash</button>
                </div>
                `);

            }
        }); //it for the cash button to appear
        //paypal
        $('#paypal').change(function () {
            if ($(this).is(':checked')) {
                $('#paymentMethodContainer').html(`
                
               
                <button  type="submit" name="submit" id="paypalbutton"
                        style="width: 90%; height: 30px; background-color: rgb(202, 202, 202); color: white; border-radius: 20px;">Proceed
                        with Paypal</button>
        `); //it for the paypal button to appearf
         }
        });*/
        //ewallet pak malau
        /*$('#ewallet').change(function () {
            if ($(this).is(':checked')) {
                $('#paymentmethod').html(`
                <div id="paymentmethod" style="text-align: center;">
                <button id="checkout-frm" type="submit" style="width: 90%; height: 30px; background-color: rgb(202, 202, 202); color: white; border-radius: 20px;">Proceed with E-Wallet</button>
                </div>
                `);
            }
        }); //it for the ewallet button to appear*/
 
       
        /*$('#googlepay').change(function () {
            if ($(this).is(':checked')) {
                $('#paymentmethod').html(`
                <div id="gpay-button" >
                `);
            }
        }); //it for the google pay button to appear*/
    });

    

    (function() {
    if (window.localStorage) {
        if (!localStorage.getItem('firstLoad')) {
            localStorage.setItem('firstLoad', true);
            window.location.reload();
        } else {
            localStorage.removeItem('firstLoad');
        }
    }
})();

</script>




<style>
     #checkout-cash {
    width: 90%;
    height: 50px; /* Increased height for better visibility */
    background-image: linear-gradient(to right, #56ab2f, #a8e063); /* Green gradient for cash */
    color: white;
    border: none;
    border-radius: 25px; /* Rounded corners */
    font-size: 18px; /* Larger font size */
    font-weight: bold; /* Bold font for emphasis */
    cursor: pointer; /* Changes cursor to indicate it's clickable */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
    transition: all 0.3s ease; /* Smooth transition for hover effects */
  }

  #checkout-cash:hover {
    background-image: linear-gradient(to left, #56ab2f, #a8e063); /* Inverse gradient on hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* More pronounced shadow on hover */
  }
    .bordermain * {

        /* Reset styles */
        /* Style for the main border */
        .bordermain {
            border: 2px solid #ccc;
            padding: 20px;
            border-radius: 10px;
        }

        /* Style for the form */
        #checkout-frm {
            display: flex;
            justify-content: center;
        }

        /* Style for the left side */
        #headdiv {
            text-align: left;
            padding: 50px;
            width: 500px;
        }

        /* Style for the right side */
        .proc {
            margin-top: 50px;
            padding: 50px;
            background-color: rgb(240, 240, 240);
            min-width: 500px;
            height: 100px;
            border-radius: 25px;
        }

        /* Style for the headings */
        h1,
        h3 {
            margin: 0;
        }

        /* Style for the subheadings */
        #reset h3,
        #eighthdiv h3,
        #ninthdiv h3,
        #tenthdiv h3,
        #eleventhdiv h3 {
            margin-bottom: 10px;
        }

        /* Style for the inputs and labels */
        label {
            font-size: 13px;
            font-weight: normal;
        }

        input[type="text"],
        input[type="tel"],
        input[type="email"],
        textarea {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            width: 250px;
            margin-bottom: 10px;
        }

        /* Style for the buttons */
        button {
            width: 90%;
            height: 30px;
            background-color: rgb(202, 202, 202);
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #666;
        }

          /* Style for the toggle button */
          .toggle-btn {
            display: inline-block;
            width: 60px;
            height: 30px;
            background-color: #ccc;
            border-radius: 15px;
            position: relative;
            cursor: pointer;
        }
        /* Style for the inner circle */
        .toggle-btn::before {
            content: '';
            position: absolute;
            width: 26px;
            height: 26px;
            background-color: white;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.3s ease; 
        }
        /* Style for checked toggle button */
        .toggle-btn.checked::before {
            transform: translateX(30px) translateY(-50%);
        }
        /* Hide the checkbox */
        .toggle-btn input[type="checkbox"] {
            display: none;
        }
    }
</style>

<div class="bordermain">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    
    <form action="admin/charge.php" id="" method="GET">
        <div style="display: flex;margin-left: 20%;margin-right: 20%;margin-top:72px;">
            <div id="headdiv" style="text-align: left; padding: 50px; width: 500px;margin-top:72px;">
                <div id="seconddiv">
                    <div id="thirddiv">
                        <h1><b>Checkout</b></h1>
                    </div>
                    <div id="fourthdiv">
                        <!-- Tray self collect -->
                        <div id="fifthdiv">
                            <h3 ></h3>
                            <div id="sixthdiv">
                                <span
                                    style="margin-top:-10px !important;"></span>
                                <!---<p style="margin-top: -20px; margin-left: 40px; font-size: 15px; font-weight: normal;">Warung Satay Pak Malau Melaka</p>--->

                                <input type="radio" id="address" name="address"
                                    style="margin-left:0px;margin-top:30px !important;"
                                    value="Warung SATAY PAK MALAU, 75100 Malacca" checked>                                
                                    <span id="locationcollect"
                                    style="margin-top: -25px !important; margin-left: 0px; font-size: 15px; font-weight: normal; "></span>



                                <span
                                    style="margin-top: -50px; margin-left: 450px;"></span>
                            </div>

                            <div id="reset">
                                <h3>Collection Method</h3>
                                <img src=""
                                    style="margin-top: -40px; margin-left: 260px; position: absolute;">
                                <input type="radio" id="collect_method" name="collectionMethod" value=""
                                    checked>
                                <span id="collect_methodname" style="font-size: 15px; font-weight: normal;"> </span>
                                <p id="total_distance" style="font-size: 15px; font-weight: normal;"></p>
                                <p id="min-del" name="min-del" style="font-size: 15px; font-weight: normal;"></p>
                                <input type="hidden" id="feedelivery" name="feedelivery" />

                            </div>

                            <hr>

                            <div id="eighthdiv">
                                <h3>Date and Time</h3>
                                <span></span>
                                <p id="date-time" style="font-size: 15px; font-weight: normal; margin-top: -18px; margin-left: 0px;">
                                  </p>
                            </div>

                            <hr>

                            <div id="ninthdiv">
                                <h3>Add Your Details</h3>

                                <span><label for="name" style="font-size: 13px; font-weight: normal;"><span style="color:red;">*</span>Name</label><br>
                                    <input type="text" id="name" name="first_name" placeholder="e.g. aiman"required
                                        value="<?php echo $_SESSION['login_first_name'];?>"
                                        style="border-top: 0px; border-left: 0px; border-right: 0px; height: 30px; width: 250px;"></span>
                                <br><br>
                                <span><label for="lastname" style="font-size: 13px; font-weight: normal; visibility: ;"><span style="color:red;"></span>Last
                                        Name</label><br>
                                    <input type="text" id="lastname" name="last_name" placeholder="e.g. hakim"
                                        value="<?php echo $_SESSION['login_last_name'];?>"
                                        style="border-top: 0px; border-left: 0px; border-right: 0px; height: 30px; width: 250px; visibility: ;"></span>
                                <br><br>
                                <span>
                                    <label for="phone" style="font-size: 13px; font-weight: normal;">
                                        <span style="color:red;">*</span>Phone
                                    </label><br>
                                    <input type="number" id="phone" name="mobile" placeholder="e.g. 60125356232" required 
                                        pattern="^60\d{8,12}$" title="Please enter a valid phone number starting with '60' and having 8 to 12 digits."
                                        value="<?php echo $_SESSION['login_mobile'] ?>"
                                        style="border-top: 0px; border-left: 0px; border-right: 0px; height: 30px; width: 250px;">
                                </span>
                                <br><br>
                                <span><label for="email"
                                        style="font-size: 13px; font-weight: normal;">Email</label><br>
                                    <input type="email" id="email" name="email" placeholder="e.g. aiman@gmail.com"
                                        value="<?php echo $_SESSION['login_email'] ?>" readOnly
                                        style="border-top: 0px; border-left: 0px; border-right: 0px; height: 30px; width: 250px;"></span>
                                <br><br>



                            </div>

                            <hr>

                            <div id="tenthdiv">
                                <h3>Orders Request (Optional)</h3>
                                <textarea  name="orderrequest" id="orderrequest" placeholder="e.g extra sos kacang"
                                    style="width: 300px; height: 150px; font-size: 13px; font-weight: normal;"></textarea>
                                <br>
                                <span style="font-size: 10px; font-weight: bold;">*We will try our best to accommodate
                                    your request, fulfillment is subject to availability.</span>
                            </div><!---
                            <label class="toggle-btn">
                                <input type="checkbox">
                                <span>Discount point: <?//php echo $_SESSION['login_last_login'] ?></span>
                            </label>---->


                            <script>
                                // JavaScript to handle toggle button functionality
                                const toggleBtn = document.querySelector('.toggle-btn input[type="checkbox"]');
                                toggleBtn.addEventListener('change', function() {
                                    if (this.checked) {
                                        // If checkbox is checked, add 'checked' class to parent label
                                        this.parentNode.classList.add('checked');
                                    } else {
                                        // If checkbox is unchecked, remove 'checked' class from parent label
                                        this.parentNode.classList.remove('checked');
                                    }
                                });
                            </script>



                            <div id="eleventhdiv" style="width: 200px;">
                                <h3>Payment Method</h3>
                                <span><input type="radio" id="cash" name="paymentMethod" value="cash" checked><label
                                        for="cash" style="font-weight: bold;">Cash</label></span>
                                <img src="./assets/img/cash.png" alt="placeholder" style="float:right; object-fit: cover; background-color:white; width: 20px; height:20px;">
                                <br>
                                <span><input type="radio" id="paypal" name="paymentMethod" value="paypal"><label
                                        for="paypal" style="font-weight: bold;">Paypal</label></span>
                                <img src="./assets/img/PayPal.png" alt="placeholder" style="float:right; object-fit: cover; background-color:blue; width: 20px; height:20px;">
                                <br>
                                <span><input type="radio" id="ewallet" name="paymentMethod" value="ewallet" checked><label
                                        for="cash" style="font-weight: bold;">Pak Malau E Wallet</label></span>
                                <img src="images/ewallet.png" alt="placeholder" style="float:right; object-fit: cover; background-color:white; width: 20px; height:20px;">
                                <br>

                             <input type="hidden" name="paymentType" value="Food" />
                            </div>

                            <div id="discountdiv" style="width: 200px;">
                                <h3>Discount</h3>
                                <select name="discount" id="discount" style="width: 200px; height: 30px; font-size: 13px; font-weight: normal;">
                                    <option value="nodis">No Discount</option>
                                    <option value="dis">Discount (<span id="discountPoint"><?php echo $discount_point; ?></span>)</option>
                                </select>
                            </div>

                            <script>
                                // Function to save selected discount to local storage
                                function saveSelectedDiscount(selectedDiscount) {
                                    localStorage.setItem("selectedDiscount", selectedDiscount);
                                }

                                // Function to retrieve selected discount from local storage
                                function getSelectedDiscount() {
                                    return localStorage.getItem("selectedDiscount");
                                }

                                // Function to apply discount and update total price
                                function applyDiscount(selectedDiscount) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open("POST", "calculate_discount.php", true);
                                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                    xhr.onreadystatechange = function() {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                            var discountedTotal = parseFloat(xhr.responseText);
                                            // Update total on the webpage with discountedTotal
                                            

                                            // For example:
                                            document.getElementById("totaldis").innerText = "RM " + discountedTotal.toFixed(2);
                                        }
                                    };
                                    xhr.send("selectedDiscount=" + selectedDiscount);
                                }

                                // Event listener for dropdown change
                                document.getElementById("discount").addEventListener("change", function() {
                                    var selectedDiscount = this.value;
                                    saveSelectedDiscount(selectedDiscount); // Save selected discount to local storage
                                    applyDiscount(selectedDiscount);
                                });

                                // Set the selected option based on the stored discount when the page loads
                                window.addEventListener("load", function() {
                                    var selectedDiscount = getSelectedDiscount();
                                    if (selectedDiscount) {
                                        document.getElementById("discount").value = selectedDiscount;
                                        // Apply discount on page load if a discount was previously selected
                                        applyDiscount(selectedDiscount);
                                    }
                                });
                            </script>






                        </div>
                    </div>
                </div>
            </div>

            <div class="proc"
                style="margin-top:250px;margin-left:600px;padding: 50px; background-color: rgb(240, 240, 240); min-width: 500px; height: auto; border-radius: 25px; position:fixed; ">
                <div id="thirteenthdiv" style="margin-top: -25px;">
                    <p class="text-left">Subtotal (incl. SST) <a
                            style="float: right; margin-right: 10px; color: rgb(15, 101, 58); font-style: normal;"><b>RM
                                <?php echo number_format($_SESSION['subtotal'], 2) ?>
                            </b></a></p>
                    <p id="delfeetax" class="text-left">Delivery Fee <a
                            style="float: right; margin-right: 10px; color: rgb(15, 101, 58); font-style: normal;"><b >RM <b id="delfee"></b>
                                
                            </b></a></p>
                    <p class="text-left">Service Tax (6%) <a
                            style="float: right; margin-right: 10px; color: rgb(15, 101, 58); font-style: normal;"><b>RM
                                <?php echo number_format($_SESSION['service_tax'], 2) ?>
                            </b></a></p>
                    <p class="text-left">Total (incl. SST) <a
                            style="float: right; margin-right: 10px; color: rgb(15, 101, 58); font-style: normal;" id="totaldis"><b></b>RM
                            <?php echo number_format($_SESSION['total'], 2) ?></b>
                        </a></p>
                </div>
                <p><input type ="hidden" name="amount" value="<?php echo trim($_SESSION['total']); ?>" /> </p>
                <div id="paymentMethodContainer" style="text-align: center;">
                    <button id="checkout-cash" type="submit" name="submit" 
                        style="width: 90%; height: 30px; background-color: rgb(202, 202, 202); color: white; border-radius: 20px;">Proceed
                        with cash</button>
                        <div id="loading" style="display: none; margin-top:20px;"><button class="btn btn-primary">
  <span class="spinner-border spinner-border-sm"></span>
  Loading..
</button></div>
                </div>

            </div>
        </div>
    </form>
</div>

<script> 
//onload reset radio button 
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('cash').checked = true;

});


$('#checkout-frm').submit(function (e) {
    e.preventDefault(); //prevent the form from auto submit

     // Validate phone number pattern
     var phoneNumber = $('#phone').val();
    var phonePattern = /^60\d{6,10}$/;
    if (!phonePattern.test(phoneNumber)) {
        alert('Please enter a valid phone number starting with "60" and having 8 to 12 digits.');
        return; // Stop form submission if phone number pattern is not valid
    }

    if ($('#paypal').is(':checked')) {
        // If PayPal is selected, set the form's action to /paypal/charge.php
        $(this).attr('action', ''); 
    }
    else if ($('#ewallet').is(':checked')) {
        $(this).attr('action', 'paymentewallet.php');
    } else {
        // If PayPal is not selected, send the form data to admin/ajax.php?action=save_order
        $.ajax({
            url: "admin/ajax.php?action=save_order",
            method: 'POST',
            data: $(this).serialize(), ///serialize the form data
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Order successfully Placed.")
                    setTimeout(function () {
                        location.replace('index.php?page=order_track') //redirect to order track page
                    }, 1500); //delay 1.5 seconds
                }
            }
        });
    }
});

//cash
$('#cash').change(function () {
    if ($(this).is(':checked')) {
        $('#paymentMethodContainer').html(`
            <button id="checkout-cash" type="submit" style="width: 90%; height: 30px; background-color: rgb(202, 202, 202); color: white; border-radius: 20px;">Proceed with Cash</button>
        `);
    }
});

//paypal
$('#paypal').change(function () {
    if ($(this).is(':checked')) {
        $('#paymentMethodContainer').html(`
            <button  type="submit" name="submit" id="paypalbutton" style="width: 90%; height: 30px; background-color: rgb(202, 202, 202); color: white; border-radius: 20px;">Proceed with Paypal</button>
        `); //it for the paypal button to appear
    }
});
$('#ewallet').change(function () {
    if ($(this).is(':checked')) {
        $('#paymentMethodContainer').html(`
            <button  type="submit" name="submit" id="ewalletbutton" style="width: 90%; height: 30px; background-color: rgb(202, 202, 202); color: white; border-radius: 20px;">Proceed with Ewallet</button>
        `); //it for the paypal button to appear
    }
});


        document.addEventListener('DOMContentLoaded', function() {
    var mangkukTimunImage = localStorage.getItem('mangkukTimunImage');
    var bawangImage = localStorage.getItem('bawangImage');
    var kuahImage = localStorage.getItem('kuahImage');

    // Concatenate the text values retrieved from localStorage
    var textToDisplay = '';
    if (mangkukTimunImage) {
        textToDisplay += mangkukTimunImage + '\n';
    }
    if (bawangImage) {
        textToDisplay += bawangImage + '\n';
    }
    if (kuahImage) {
        textToDisplay += kuahImage + '\n';
    }

    // Set the concatenated text to the textarea
    document.getElementById('orderrequest').value = textToDisplay.trim();
});

</script>

<style>
  #paypalbutton {
    width: 90%;
    height: 50px; /* Increased height for better visibility */
    background-image: linear-gradient(to right, #003087, #009cde); /* PayPal brand colors */
    color: white;
    border: none;
    border-radius: 25px; /* More pronounced rounded corners */
    font-size: 18px; /* Larger font size */
    font-weight: bold; /* Bold font for better readability */
    cursor: pointer; /* Cursor changes to pointer to indicate it's clickable */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
    transition: all 0.3s ease; /* Smooth transition for hover effects */
  }
  #paypalbutton:hover {
    background-image: linear-gradient(to left, #003087, #009cde); /* Inverse gradient on hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* More pronounced shadow on hover */
  }
  #ewalletbutton {
    width: 90%;
    height: 50px; /* Increased height for better visibility */
    background-image: linear-gradient(to right, #808080, #C0C0C0); /* PayPal brand colors */
    color: white;
    border: none;
    border-radius: 25px; /* More pronounced rounded corners */
    font-size: 18px; /* Larger font size */
    font-weight: bold; /* Bold font for better readability */
    cursor: pointer; /* Cursor changes to pointer to indicate it's clickable */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
    transition: all 0.3s ease; /* Smooth transition for hover effects */
  }
  #ewalletbutton:hover {
    background-image: linear-gradient(to left, #808080, #C0C0C0); /* Inverse gradient on hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* More pronounced shadow on hover */
  }

  #paypalbutton:hover {
    background-image: linear-gradient(to left, #003087, #009cde); /* Inverse gradient on hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* More pronounced shadow on hover */
  }
</style>




<!--script google pay-->
<script async SRC="https://pay.google.com/gp/p/js/pay.js" onload="onGooglePayLoaded()"></script>

<script>
    const tokenizationSpecification = {
        type: 'PAYMENT_GATEWAY',
        parameters: {
            gateway: 'example',
            gatewayMerchantId: 'exampleGatewayMerchantId'
        }
    };
    const cardPaymentMethod = {
        type: 'CARD',
        tokenizationSpecification: tokenizationSpecification,
        parameters: {
            allowedAuthMethods: ['PAN_ONLY', 'CRYPTOGRAM_3DS'],
            allowedCardNetworks: ['VISA', 'MASTERCARD']
        }

    };
    const googlePayConfiguration = {
        apiVersion: 2,
        apiVersionMinor: 0,
        allowedPaymentMethods: [cardPaymentMethod],
    };
    let googlePayClient;

    function onGooglePayLoaded() {
        googlePayClient = new google.payments.api.PaymentsClient({
            environment: 'TEST'
        });
        googlePayClient.isReadyToPay(googlePayConfiguration)
            .then(response => {
                if (response.result) {
                    //add google pay button
                    createAndAddButton();
                } else {
                    //the current user cannot pay using google pay

                }
            })
            .catch(error => console.error('isReadyToPay', error));

    }

    function createAndAddButton() {
        const googlePayButton = googlePayClient.createButton({
            onclick: onGooglePaymentButtonClicked,
        });
        document.getElementById('gpay-button').appendChild(googlePayButton);
    }


    function onGooglePaymentButtonClicked() {
        const paymentDataRequest = {
            ...googlePayConfiguration
        };
        paymentDataRequest.merchantInfo = {
            merchantId: 'BCR2DN4TQHELN5BL',
            merchantName: 'FYP PROJECT PAK MALAU SATAY',
        };
        paymentDataRequest.transactionInfo = {
            totalPriceStatus: 'FINAL',
            totalPrice: '<?php echo number_format($_SESSION['total'], 2); ?>',
            currencyCode: '<?php echo $currency; ?>'
        };
        googlePayClient.loadPaymentData(paymentDataRequest)
            .then(paymentData => processPaymentData(paymentData))
            //handle the payment data
            .catch(error => console.error('loadPaymentData error: ', error));
    }

    function processPaymentData(paymentData) {
        fetch(ordersEndpointUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json' //this is the header that will be sent to the server
            },
            body: paymentData
        })
    }
    
</script>