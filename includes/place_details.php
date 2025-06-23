<?php
include 'header.php';
include '../backend/db_connection.php';

if (!isset($_GET['id'])) {
    echo "Place ID not provided.";
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT name, description, latitude, longitude FROM places WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$place = $result->fetch_assoc();

if (!$place) {
    echo "Place not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["photo"])) {
    $targetDir = "../uploads/";
    $fileName = basename($_FILES["photo"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
        $relativePath = str_replace("../", "", $targetFile);
        $stmt = $conn->prepare("INSERT INTO place_photos (place_id, photo_path) VALUES (?, ?)");
        $stmt->bind_param("is", $id, $relativePath);
        $stmt->execute();
    }
}

$photoStmt = $conn->prepare("SELECT photo_path FROM place_photos WHERE place_id = ?");
$photoStmt->bind_param("i", $id);
$photoStmt->execute();
$photosResult = $photoStmt->get_result();
$photos = $photosResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_photo"])) {
    $photoPath = $_POST["delete_photo"];

    $fullPath = "../" . $photoPath;
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }

    $stmt = $conn->prepare("DELETE FROM place_photos WHERE photo_path = ?");
    $stmt->bind_param("s", $photoPath);
    $stmt->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($place['name']) ?></title>
  <link rel="stylesheet" href="../styles/header.css" />
  <link rel="stylesheet" href="../styles/place_details.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>

<div class="details-container">
  <h1><?= htmlspecialchars($place['name']) ?></h1>
  <p><?= htmlspecialchars($place['description']) ?></p>

  <div id="map"></div>

<form method="POST" enctype="multipart/form-data" class="photo-upload-form">
  <label class="custom-file-upload">
    <input type="file" name="photo" accept="image/*" required>
    Вибрати файл
  </label>
  <button type="submit">Завантажити фото</button>
</form>

<?php if (count($photos) > 0): ?>
  <div class="slider-container">
    <button class="slider-btn prev-btn" onclick="scrollSlider(-1)">‹</button>
    
    <div class="photo-slider" id="photo-slider">
  <?php foreach ($photos as $photo): ?>
  <div class="photo-wrapper">
    <img src="../<?= htmlspecialchars($photo['photo_path']) ?>" alt="Фото місця">
    <form method="POST" class="delete-form" onsubmit="return confirm('Ви дійсно хочете видалити це фото?');">
      <input type="hidden" name="delete_photo" value="<?= htmlspecialchars($photo['photo_path']) ?>">
      <button type="submit" class="delete-btn">✖</button>
    </form>
  </div>
<?php endforeach; ?>

    </div>
    
    <button class="slider-btn next-btn" onclick="scrollSlider(1)">›</button>
  </div>
<?php else: ?>
  <p class="no-photos">Немає фото для цього місця.</p>
<?php endif; ?>

<div id="photo-modal" class="photo-modal">
  <span class="modal-close" onclick="closeModal()">&times;</span>
  <img class="modal-content" id="modal-img">
  <button class="modal-nav left" onclick="changeModalPhoto(-1)">‹</button>
  <button class="modal-nav right" onclick="changeModalPhoto(1)">›</button>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  const placeLocation = [<?= floatval($place['latitude']) ?>, <?= floatval($place['longitude']) ?>];
  const map = L.map('map').setView(placeLocation, 15);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  L.marker(placeLocation).addTo(map)
    .bindPopup("<?= htmlspecialchars($place['name']) ?>")
    .openPopup();

    function scrollSlider(direction) {
    const slider = document.getElementById('photo-slider');
    const scrollAmount = slider.offsetWidth / 3; 
    slider.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
  }

const modal = document.getElementById("photo-modal");
const modalImg = document.getElementById("modal-img");
const sliderImages = document.querySelectorAll('#photo-slider img');
let currentImgIndex = 0;

sliderImages.forEach((img, index) => {
  img.addEventListener("click", () => {
    openModal(index);
  });
});

function openModal(index) {
  currentImgIndex = index;
  modal.style.display = "flex";
  modalImg.src = sliderImages[currentImgIndex].src;
}

function closeModal() {
  modal.style.display = "none";
}

function changeModalPhoto(direction) {
  currentImgIndex += direction;
  if (currentImgIndex < 0) currentImgIndex = sliderImages.length - 1;
  if (currentImgIndex >= sliderImages.length) currentImgIndex = 0;
  modalImg.src = sliderImages[currentImgIndex].src;
}

modal.addEventListener("click", function(e) {
  if (e.target === modal) {
    closeModal();
  }
});


document.addEventListener("keydown", function(e) {
  if (e.key === "Escape") {
    closeModal();
  }
});


let zoomLevel = 1;
const minZoom = 0.2;  
const maxZoom = 4;    

modalImg.addEventListener('wheel', function(e) {
  if (e.deltaY < 0) {
    zoomLevel += 0.1; 
    if (zoomLevel > maxZoom) zoomLevel = maxZoom; 
  } else {
    zoomLevel = zoomLevel > minZoom ? zoomLevel - 0.1 : minZoom;
  }
  modalImg.style.transform = `scale(${zoomLevel})`;
  e.preventDefault();
});



</script>

</body>
</html>
