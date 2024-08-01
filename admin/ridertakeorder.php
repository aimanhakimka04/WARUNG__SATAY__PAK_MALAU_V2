<!---diliver status-->


<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet Control Geocoder CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<!-- Leaflet Routing Machine CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- Leaflet Control Geocoder JavaScript -->
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<!-- Leaflet Routing Machine JavaScript -->
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>





<div class="takeorder">

    <button id="getCurrentLocationBtn" class="buttondes word"><img src="imgicon\ping.png" style="height: 15px;width: 15px;"></button>

    <div id="map" style="width:100%; height:100%;"></div>
    
    <div class="detailview">
        <p class="tit">
            Address
        </p>
        <p style="word-wrap: break-word;">
            mmu melaka
        </p>
        <p class="tit"  style="margin-top: -111px; margin-left: 155px;">
            Order ID : 
        </p>
        <p style=" margin-left: 155px;">
            12345
        </p>
        <p class="tit">
            Total distance and times:
        </p>
        <p id="totaldis">
             
        </p>
        <button class="buttondes" class="word" style="margin-top: -111px; margin-left: 150px;" onclick="vieworderpage()"><a href="vieworder.html">View Order</a></button>


        <div class="firstb">
            <button class="buttondes" class="word">Order Arrives</button>
            <button class="buttondes" class="word">Order Cancel</button>
        </div>

        <button class="back" onclick="chooseorderpage()">
            <a href="chooseorder.html" class="word">Previous Page</a>
        </button>
    </div>
</div>

<style>
    #getCurrentLocationBtn {
        position: absolute;
        top: 74px;
        left: 10px;
        width:34px;
        height:34px;
        z-index: 1000; /* Ensure the button appears above other elements */
        border: 2px solid rgb(196, 196, 196);
        background-color: white;
    }


    #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

    .takeorder {
        position: relative; 
        width: 100%;
        height: 100%;
    }

    .detailview {
        position: absolute; 
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%); 
        z-index: 1; 
        padding: 20px;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-top: 250px;
        width: 300px;
        height: 350px;
    }

    .detailview button {
        margin-right: 10px;
        padding: 10px 20px;
        font-size: 16px;
    }
    .buttondes {
        border-radius: 10px;
        background-color: #4781c0db;
        color: #fff;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-weight: bold;
        font-size: 15px;
        height: 60px;
        width: 140px;

    }

    .back
    {
        border-radius: 10px;
        background-color: #4781c0db;
        color: #fff;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-weight: bold;
        font-size: 15px;
        height: 60px;
        width: 290px;
        margin-top: 20px;
    }

    .word
    {
        text-decoration: none;
        color: white;
    }

    .firstb
    {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: -30px;
    }
    
    p
    {
        height:60px;
        width: 140px;
    }
    
    .tit
    {
        height: 20px;
        width: 140;
        font-size: 17px;
        font-weight: bold;
    }
</style>
<script>
    var map = L.map('map');
    var initialCoordinates = [2.1896, 102.2501]; // Default coordinates: Melaka
    var formplaces = "2.210627806573058, 102.25250078670882";
    var tolocations = "2.2495051283474568, 102.27618202334175";
    
    // Set up the map with default coordinates and zoom level
    map.setView(initialCoordinates, 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
  
    // Function to update map view based on user's current location
    function updateMapLocation(position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;
      map.setView([latitude, longitude], 13); // Update map view to center on current location
  
      // Add marker icon at the center of the map
      var icon = L.icon({
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        tooltipAnchor: [16, -28],
        shadowSize: [41, 41]
      });
      L.marker([latitude, longitude], {icon: icon}).addTo(map);
    }
  
    // Function to handle errors when getting user's location
    function handleLocationError(error) {
      console.error('Error getting user location:', error);
    }
    
    // Draw route between "from" and "to" locations
    var control = L.Routing.control({
    waypoints: [
        L.latLng(formplaces.split(',')[0], formplaces.split(',')[1]),
        L.latLng(tolocations.split(',')[0], tolocations.split(',')[1])
    ],
    routeWhileDragging: true
    }).addTo(map);


    // Listen for route event to get route summary
    control.on('routesfound', function(e) {
        var routes = e.routes;
        if (routes.length > 0) {
            var route = routes[0];
            var summary = route.summary;
            document.getElementById('totaldis').innerHTML = (summary.totalDistance / 1000).toFixed(2) + " km " + Math.floor(summary.totalTime / 60) + " minutes";
        }
    });

    // Get the button element
    var getCurrentLocationBtn = document.getElementById('getCurrentLocationBtn');

    // Add click event listener to the button
    getCurrentLocationBtn.addEventListener('click', function() {
        // Check if geolocation is available in the browser
        if (navigator.geolocation) {
            // If geolocation is available, get user's current position
            navigator.geolocation.getCurrentPosition(updateMapLocation, handleLocationError);
        } else {
            console.error('Geolocation is not supported by this browser.');
        }
    });


    // Check if geolocation is available in the browser
    if (navigator.geolocation) {
      // If geolocation is available, get user's current position
      navigator.geolocation.getCurrentPosition(updateMapLocation, handleLocationError);
    } else {
      console.error('Geolocation is not supported by this browser.');
    }

    function vieworderpage()
    {
        location.href = "vieworder.html";
    }

    function chooseorderpage()
    {
        location.href = "chooseorder.html";
    }

    //get current location
    
  </script>