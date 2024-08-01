<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Set Your Location</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap");

    .input-container {
      position: relative;
      width: 720px;
      margin: 20px auto;
      margin-left: 483px;
    }

    .location-input {
      width: calc(100% - 90px); 
      height: 50px;
      border: 1px solid #d1cbcbe0; 
      border-bottom: 1px solid rgb(194, 194, 194);
      display: block;
      border-radius: 10px;
      font-family: "Noto Sans", sans-serif;
    }

    .clear-button {
      position: absolute;
      top: 50%;
      right: 35px; 
      transform: translateY(-50%);
      border: none;
      background: none;
      padding: 0;
      cursor: pointer;
      border: 1px solid #d1cbcbe0;
      border-radius: 10px;
    } 

    .gps-location {
      position: absolute;
      top: 50%;
      right: -13px;
      transform: translateY(-50%);
      border: none;
      background: none;
      padding: 0;
      cursor: pointer;
      background-color: red;
      border-radius: 30%;
    }

    .confirm {
      right: 0;
      background-color: #4caf50;
      color: white;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      right: -220px;      
      padding: 16px;   
      border-radius: 20px;
      width: 200px;
      height: 50px;
    }

    #map {
      width: 940px;
      height: 400px;
      margin-top: 20px;
      margin: auto;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<h3 style="text-align: center; margin: auto">Set Your Location</h3>
<div class="input-container">
  <input
    type="text"
    placeholder="Enter your address or building name at the search button in the map or marking your location."
    class="location-input"
    id="search-input"
    readonly
  />
  <button class="clear-button" onclick="clearInput()">
    <img src="images/clear_icon.png" alt="Clear" />
  </button>
  <button class="gps-location" onclick="getLocation()">
    <img src="images/gps_icon.png" alt="GPS" />
  </button>
  <button class="confirm" onclick="confirmLocation()">Confirm</button>
</div>

<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
  // Using get method to take the value from the URL and store it in the variable ['method']
  const urlParams = new URLSearchParams(window.location.search);
  const method = urlParams.get('method');

  // Define bounds for Malacca (approximate coordinates)
  const malaccaBounds = [
    [2.086, 102.190], // Southwest corner
    [2.471, 102.490]  // Northeast corner
  ];

  // Initialize map and set bounds
  var map = L.map('map').fitBounds(malaccaBounds);

  var redMarker = null; // Variable to store the red marker instance

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
  }).addTo(map);

  // Restrict map movements to Malacca bounds
  map.setMaxBounds(malaccaBounds);
  map.on('drag', function() {
    map.panInsideBounds(malaccaBounds, { animate: false });
  });

  // Function to clear input field
  function clearInput() {
    document.querySelector(".location-input").value = "";
    clearMarker();
  }

  // Function to clear the red marker
  function clearMarker() {
    if (redMarker) {
      map.removeLayer(redMarker);
      redMarker = null;
    }
  }

  // Function to get user's location
  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      alert("Geolocation is not supported by this browser.");
    }
  }

  // Function to show user's location on the map and insert address into input field
  function showPosition(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    
    // Check if user's location is within Malacca bounds
    if (lat < malaccaBounds[0][0] || lat > malaccaBounds[1][0] || lng < malaccaBounds[0][1] || lng > malaccaBounds[1][1]) {
      alert("Your location is outside Malacca.");
      return;
    }

    // Reverse geocoding to get address from coordinates
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
      .then(response => response.json())
      .then(data => {
        var address = data.display_name;
        document.querySelector(".location-input").value = address;
      })
      .catch(error => {
        console.error('Error:', error);
      });

    map.setView([lat, lng], 13);
    updateRedMarker(lat, lng);
  }

  // Function to update the red marker position or create a new one if it doesn't exist
  function updateRedMarker(lat, lng) {
    clearMarker();
    redMarker = L.marker([lat, lng], { icon: redIcon }).addTo(map)
      .bindPopup("Your location").openPopup();
  }

  // Define custom icon for red marker
  var redIcon = L.icon({
    iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  // Leaflet Control Geocoder setup
  var geocoder = L.Control.geocoder({
    defaultMarkGeocode: false,
    geocoder: new L.Control.Geocoder.Nominatim({
      bounds: malaccaBounds,
      countrycodes: 'MY' // Country code for Malaysia
    })
  }).on('markgeocode', function(e) {
    var latlng = e.geocode.center;
    var lat = latlng.lat;
    var lng = latlng.lng;
    var address = e.geocode.name;

    // Check if the geocoded location is within Malacca bounds
    if (lat < malaccaBounds[0][0] || lat > malaccaBounds[1][0] || lng < malaccaBounds[0][1] || lng > malaccaBounds[1][1]) {
      alert("Selected location is outside Malacca.");
      return;
    }

    // Clear input field
    clearInput();

    // Set input value to the selected address
    document.querySelector(".location-input").value = address;

    // Update map view and red marker
    map.setView(latlng, 13);
    updateRedMarker(lat, lng);
  }).addTo(map);

  // Event listener for map click
  map.on('click', function(e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;

    // Check if clicked location is within Malacca bounds
    if (lat < malaccaBounds[0][0] || lat > malaccaBounds[1][0] || lng < malaccaBounds[0][1] || lng > malaccaBounds[1][1]) {
      alert("Selected location is outside Malacca.");
      return;
    }

    // Clear input field
    clearInput();

    // Reverse geocoding to get address from coordinates
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
      .then(response => response.json())
      .then(data => {
        var address = data.display_name;
        document.querySelector(".location-input").value = address;
      })
      .catch(error => {
        console.error('Error:', error);
      });

    updateRedMarker(lat, lng);
  });

  confirmLocation = () => {
    // Remove the previous location
    localStorage.removeItem("location");
    localStorage.removeItem("method_location");
    let location = document.querySelector(".location-input").value;
    if (location === "") {
      alert("Please enter your location.");
    } else {
      // Store location in local storage
      localStorage.setItem("location", location);
      // Gain the local storage value
      let userLocation = localStorage.getItem("location");
      localStorage.setItem("method_location", method);
  
      if (userLocation) {
        document.getElementById('locationDisplay').textContent = userLocation;
      }

      // Redirect to home page
      window.location.href = "index.php?page=home";
    }
  };
</script>

</body>
</html>
