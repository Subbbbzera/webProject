<?php
include 'header.php';
include '../backend/db_connection.php';

$result = $conn->query("SELECT id, name, latitude, longitude FROM places WHERE is_public = 1");
$places = [];

while ($row = $result->fetch_assoc()) {
    $places[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Public Sports Grounds</title>
  <link rel="stylesheet" href="../styles/header.css">
  <link rel="stylesheet" href="../styles/all_place.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>

<div class="map-page-container">
  <h1>All Public Sports Grounds</h1>
  

<div class="search-container">
  <input type="text" id="search" placeholder="Введіть місто, село, адресу..." class="address-search">
  <button id="searchButton">Пошук</button>
  <p id="notFoundMessage" style="color: red; display: none;">Населений пункт не знайдено.</p>
</div>


<div id="map"></div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<script>
  const map = L.map('map').setView([50.4501, 30.5234], 12); // Київ

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '',
    maxZoom: 18
  }).addTo(map);

  const places = <?= json_encode($places) ?>;
  const markers = [];

  places.forEach(place => {
    const marker = L.marker([parseFloat(place.latitude), parseFloat(place.longitude)])
      .bindPopup(`<b>${place.name}</b><br><a href="place_details.php?id=${place.id}">View Details</a>`)
      .addTo(map);
    marker.placeName = place.name.toLowerCase();
    markers.push(marker);
  });

  const searchInput = document.getElementById('search');
  const searchButton = document.getElementById('searchButton');
  const notFoundMessage = document.getElementById('notFoundMessage');
  let searchMarker;

  searchButton.addEventListener('click', () => {
    const query = searchInput.value.trim().toLowerCase();
    notFoundMessage.style.display = 'none';

    let foundLocal = false;

    markers.forEach(marker => {
      if (query === '' || marker.placeName.includes(query)) {
        if (!map.hasLayer(marker)) {
          marker.addTo(map);
        }
        if (!foundLocal && query !== '') {
          map.setView(marker.getLatLng(), 14);
          marker.openPopup();
          foundLocal = true;
        }
      } else {
        map.removeLayer(marker);
      }
    });

    if (searchMarker) {
      map.removeLayer(searchMarker);
      searchMarker = null;
    }

    if (foundLocal || query === '') return;

    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`;

    fetch(url)
      .then(response => response.json())
      .then(data => {
        if (data.length > 0) {
          const lat = parseFloat(data[0].lat);
          const lon = parseFloat(data[0].lon);
          const displayName = data[0].display_name;

          map.setView([lat, lon], 14);
          searchMarker = L.marker([lat, lon])
            .addTo(map)
            .bindPopup(`📍 <b>${displayName}</b>`)
            .openPopup();
        } else {
          notFoundMessage.style.display = 'block';
        }
      })
      .catch(err => {
        console.error('Помилка при пошуку:', err);
        notFoundMessage.textContent = "Сталася помилка при пошуку.";
        notFoundMessage.style.display = 'block';
      });
  });
</script>


</body>
</html>
