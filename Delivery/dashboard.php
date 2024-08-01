<?php include '../admin/header.php'; 
use Google\Service\CloudControlsPartnerService\Console;?>
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

<?php
include '../admin/db_connect.php';
$rider_id = $_SESSION['login_id'];

$qry = $conn->query("SELECT * FROM rider WHERE status_delivery = 1 AND staff_id = $rider_id");
if ($qry->num_rows > 0) {
    $rider = $qry->fetch_assoc(); // Get the rider's information
    $order_id = $rider['order_id'];
    $_SESSION['order_id'] = $order_id;
   //seperate the location 2.25003,102.27656 to 2.25003 and 102.27656
   $qryorder = $conn->query("SELECT * FROM orders where id = $order_id");
   $orderdb = $qryorder->fetch_assoc();
    $location = explode(',', $orderdb['address']);
    $lat = $location[0];
    $lon = $location[1];
} else {
    $order_id = NULL;
}
?>

<title>Rider Tracking and Order Information</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<style>
  .page-body {
    font-family: Arial, sans-serif;
    margin: 10px;
    margin-top: -60px;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background: #f0f2f5;
  }

  #map {
    height: 60vh;
    width: 80%;
    margin-bottom: 20px;
    border: 2px solid #ccc;
    border-radius: 10px;
  }

  .info-container {
    display: flex;
    justify-content: space-around;
    width: 80%;
    max-width: 1200px;
  }

  .info-panel {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 48%;
  }

  .info-panel h2 {
    margin: 0 0 10px;
    font-size: 24px;
    color: #333;
  }

  .info-panel p {
    margin: 5px 0;
    font-size: 18px;
    color: #555;
  }



  .no-order-message {
    font-size: 20px;
    color: red;
    text-align: center;
    margin-top: 20px;
  }
</style>

<div class="page-body">
  <?php if ($order_id): ?>
    <div id="map"></div>
    <div class="info-container">
      <div class="info-panel">
        <h2>Rider Tracking Information</h2>
        <p><strong>Estimated Distance:</strong> <span id="distance">-- km</span></p>
        <p><strong>Estimated Time:</strong> <span id="time">-- mins</span></p>
        <button class="start-btn" onclick="startTracking()">Start Live Tracking</button>
      </div>
      <div class="info-panel">
        <h2>Order Information</h2>
        <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
        
        <div class="d-grid gap-2">
        <button class="btn btn-sm btn-primary view_customer btn-lg" data-id="<?php echo $order_id ?>">Customer Detail</button>
        <button class="btn btn-sm btn-success btn-lg start-dcomplete" data-id="<?php echo $order_id ?>">Complete Order</button>    
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="no-order-message">
      No delivering order... please check the delivery job page to check the delivery order.
    </div>
  <?php endif; ?>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
  <?php if ($order_id): ?>
    $('.start-dcomplete').click(function() {
      uni_modal('', 'completedeliver.php?order_id=' + $(this).attr('data-id'))
    });
    $('.view_customer').click(function() {
        uni_modal('', '../admin/customerorderdetail.php?id=' + $(this).attr('data-id'))
    })

    var map;
    var latcustomer = <?php echo $lat; ?>;
    var loncustomer = <?php echo $lon; ?>;
    var staticCoordinate = [latcustomer, loncustomer]; // Static coordinate
    var routingControl; // Variable to store the routing control instance
    var userMarker; // Variable to store the user's marker
    var watchId; // Variable to store the watch position ID
    var lastPosition = null; // Store the last position
    var distanceElement = document.getElementById('distance');
    var timeElement = document.getElementById('time');
    var order_id = <?php echo $order_id; ?>; // PHP variable to JavaScript

    // Create map
    map = L.map('map').setView(staticCoordinate, 13);

    // Add base layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Function to add red marker for static coordinate
    function addRedMarker(coord) {
      var redMarker = L.marker(coord, {
        icon: L.icon({
          iconUrl: 'https://img.icons8.com/ios-filled/50/FA5252/marker.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
        })
      }).addTo(map);
      redMarker.bindPopup('Static Coordinate').openPopup();
    }

    // Call the function with the static coordinate
    addRedMarker(staticCoordinate);

    // Start live tracking
    function startTracking() {
      if (navigator.geolocation) {
        watchId = navigator.geolocation.watchPosition(updatePosition, showError, {
          enableHighAccuracy: true,
          maximumAge: 0,
          timeout: 5000 // 5 seconds to timeout
        });
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    // Update position and routing
    function updatePosition(position) {
      var lat = position.coords.latitude; // Get latitude from the position object
      var lon = position.coords.longitude;
      var userLocation = [lat, lon];
      console.log(`User location: ${lat}, ${lon}`); // Debugging: log the user's location

      // Send the location to the server
      updateLocationOnServer(lat, lon);

      // If userMarker doesn't exist, create it
      if (!userMarker) {
        userMarker = L.marker(userLocation, {
          icon: L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
          })
        }).addTo(map);
        userMarker.bindPopup('Your Location').openPopup();
      } else {
        // Update marker position
        userMarker.setLatLng(userLocation);
      }

      // Center map on user's location
      map.setView(userLocation, 13);

      // Only update the route if the position has changed significantly
      if (!lastPosition || Math.abs(lastPosition.lat - lat) > 0.001 || Math.abs(lastPosition.lon - lon) > 0.001) {
        lastPosition = {
          lat: lat,
          lon: lon
        };

        // Add or update routing control
        if (!routingControl) {
          routingControl = L.Routing.control({
            waypoints: [
              L.latLng(userLocation),
              L.latLng(staticCoordinate)
            ],
            routeWhileDragging: true,
            createMarker: function() {
              return null;
            } // Disable automatic markers
          }).addTo(map);

          routingControl.on('routesfound', function(e) {
            var routes = e.routes;
            if (routes.length > 0) {
              var summary = routes[0].summary; // Use the first route found
              var distance = summary.totalDistance / 1000; // Convert to kilometers
              var time = summary.totalTime / 60; // Convert to minutes

              console.log(`Distance: ${distance.toFixed(2)} km, Time: ${time.toFixed(2)} minutes`);
              distanceElement.textContent = `${distance.toFixed(2)} km`;
              timeElement.textContent = `${time.toFixed(2)} mins`;
            }
          });
        } else {
          routingControl.setWaypoints([
            L.latLng(userLocation),
            L.latLng(staticCoordinate)
          ]);
        }
      }
    }

    // Function to send the location to the server
    function updateLocationOnServer(lat, lon) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "update_location.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            console.log("Server response: " + xhr.responseText); // Log the server response
          } else {
            console.error("Error updating location on server. Status: " + xhr.status);
          }
        }
      };
      xhr.onerror = function() {
        console.error("Request failed");
      };
      console.log("Sending data: order_id=" + order_id + "&latitude=" + lat + "&longitude=" + lon); // Log the data being sent
      xhr.send("order_id=" + order_id + "&latitude=" + lat + "&longitude=" + lon);
    }

    // Error handling for geolocation
    function showError(error) {
      switch (error.code) {
        case error.PERMISSION_DENIED:
          alert("User denied the request for Geolocation.");
          break;
        case error.POSITION_UNAVAILABLE:
          alert("Location information is unavailable.");
          break;
        case error.TIMEOUT:
          alert("The request to get user location timed out.");
          break;
        case error.UNKNOWN_ERROR:
          alert("An unknown error occurred.");
          break;
      }
    }

    // Complete delivery order function (placeholder for actual implementation)
    function completeDelivery() {
      alert("Delivery order completed!");
    }
  <?php endif; ?>
</script>
