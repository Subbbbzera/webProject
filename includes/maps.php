<?php
session_start();
include 'header.php';
include '../backend/db_connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT id, name, created_at FROM places WHERE user_id = ? ORDER BY created_at DESC");
$result->bind_param("i", $user_id);
$result->execute();
$rows = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Places to Train</title>
  <link rel="stylesheet" href="../styles/header.css" />
  <link rel="stylesheet" href="../styles/maps.css" />
</head>
<body>

<div class="container">
  <h1>Your Places to Train</h1>

  <div class="places-section">
    <div class="place-header">
      <div class="place-name">Name</div>
      <div class="place-created">Created</div>
    </div>

    <?php while($row = $rows->fetch_assoc()): ?>
      <div class="place-entry">
        <div class="place-name">
          <a href="place_details.php?id=<?= urlencode($row['id']) ?>" class="place-link">
            <?= htmlspecialchars($row['name']) ?>
          </a>
        </div>
        <div class="place-created"><?= htmlspecialchars($row['created_at']) ?></div>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="link-to-newmap">
    <p>Want to add your place? <a href="newmaps.php" class="link-text">Click here to add</a> or <a href="all_place.php" class="link-text">see public places</a></p>
  </div>
</div>

</body>
</html>
