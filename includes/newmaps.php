<?php 
include 'header.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Find a Place to Train</title>
  <link rel="stylesheet" href="../styles/newmaps.css" />
  <link rel="stylesheet" href="../styles/header.css" />
  <link rel="stylesheet" href="../styles/footer.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
<div class="container">
  <h1>Find a place to train</h1>
  <div class="maps-section">
    <form class="form-section" action="../backend/save_place.php" method="POST">
      <input type="text" name="name" placeholder="Place name" class="input1" required autocomplete="off">
      <input type="text" name="description" placeholder="Place description" class="input2" required autocomplete="off">
      <label class="checkbox-label">
        <input type="checkbox" name="is_public" value="1">
        Make it public?
      </label>
      <input type="hidden" name="latitude" id="latitude">
      <input type="hidden" name="longitude" id="longitude">
      <button type="submit" class="button">SAVE</button>
    </form>
    <div id="map"></div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  const defaultLocation = [49.0, 32.0];  
  const map = L.map('map').setView(defaultLocation, 6);  
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '',
    maxZoom: 19
  }).addTo(map);
  let marker;
  map.on('click', function(e) {
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;
    document.getElementById("latitude").value = lat;
    document.getElementById("longitude").value = lng;
    if (marker) marker.remove();
    marker = L.marker([lat, lng]).addTo(map);
  });
</script>

<?php include 'footer.php';?>

</body>
</html>
