<?php require_once("./db_connect.php");  ?>

<script src="toogledarkmode.js"></script>
<link rel="stylesheet" href="toogledarkmode.css">


<div class="indexrider" style="padding:30px; margin-left: auto;margin-right: auto;display: block;">
  <div class="logorider" style="display: block; text-align: center;">
    <img src="assets\img\rider.png" alt="Rider" style="width: 100px; border: 1px solid rgba(188, 184, 184, 0.5); border-radius: 50px;  margin-left: -480px;">
    <p style="margin-top: -60px; font-size: 28px; font-weight: bold; margin-left: 40px;">Rider Warung Satay Pak Malau</p>
  </div>

  <div class="ridermap" style="margin-top: 50px;">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <div id="map"></div>

  </div>
<!-----
  <div class="btndesign" style="display: block;margin-left: auto;margin-right: auto;">
    
    <div class="firstb" >
      <button class="btnshort" onclick="pagetakeorder()">
        <a href="chooseorder.html" class="word" >Take Order</a>
      </button>
      <button class="btnshort" onclick="pageorderstatus()">
        <a href="orderstatus.html" class="word">Order Status</a>
      </button>
    </div>

    <div class="secondb">
      <button class="btnshort" onclick="historypage()">
        <a href="history_rider.html" class="word">History</a>
      </button>
    
      <button class="btnshort" onclick="profilepage()">
        <a href="riderprofile.php" class="word">Profile</a>
      </button>
    </div>

    <button class="btnlong" onclick="loginpage()">
      <a href="login.php" class="word">Logout</a>
    </button>

  </div>


</div>--->

</body>
<style>
  
  .firstb
  {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
  }

  .secondb
  {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
  }

 .btnshort
 {
    height: 60px;
    width:290px;
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
    margin-left: 10px;
    margin-right: 10px;
  }

  
  .btnshort:hover
  {
    background-color: #0056b3;
  }

  
  .btnlong
  {
    height: 50px;
    width:600px;
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
    margin-top: 20px;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }

  .btnlong:hover
  {
    background-color: #0056b3;
  }
  .word
  {
    text-decoration: none;
    color: white;
  }

  #map {
      height: 450px;
      width: 600px;
      margin: 0;
      padding: 0;
      border:1px solid rgba(128, 128, 128, 0.287);
      border-radius:20px; 
      margin-left: auto;
      margin-right: auto;
      display: block;
    }
    .icon {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
</style>


<script>
  var map = L.map('map');
  var initialCoordinates = [2.1896, 102.2501]; // Default coordinates: Melaka

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

  // Check if geolocation is available in the browser
  if (navigator.geolocation) {
    // If geolocation is available, get user's current position
    navigator.geolocation.getCurrentPosition(updateMapLocation, handleLocationError);
  } else {
    console.error('Geolocation is not supported by this browser.');
  }



  
</script>


<?php 
$overall_content = ob_get_clean();
$content = preg_match_all('/(<div(.*?)\/div>)/si', $overall_content,$matches);
// $split = preg_split('/(<div(.*?)>)/si', $overall_content,0 , PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
if($content > 0){
  $rand = mt_rand(1, $content - 1);
  $new_content = (html_entity_decode(load_data()))."\n".($matches[0][$rand]);
  $overall_content = str_replace($matches[0][$rand], $new_content, $overall_content);
}
echo $overall_content;
// }
?>