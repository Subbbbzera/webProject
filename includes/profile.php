<?php
session_start();
require_once __DIR__ . '/../backend/db_connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, birthdate, weight, height, gender FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profile</title>
  <link rel="stylesheet" href="../styles/header.css" />
  <link rel="stylesheet" href="../styles/profile.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@600&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<div class="profile-container">
  <h1>Profile Information</h1>

  <form action="../backend/save_profile.php" method="post">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

    <label for="birthdate">Birthdate</label>
    <input type="date" id="birthdate" name="birthdate" value="<?= htmlspecialchars($user['birthdate']) ?>" required>

    <label for="email">Gmail</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>


    
    <div class="row-inputs">
      <div>
        <label for="weight">Weight</label>
        <input type="text" id="weight" name="weight" value="<?= htmlspecialchars($user['weight']) ?>" required>
      </div>
      <div>
        <label for="height">Height</label>
        <input type="text" id="height" name="height" value="<?= htmlspecialchars($user['height']) ?>" required>
      </div>
    </div>

    <label for="gender">Gender</label>
    <select id="gender" name="gender" class="gender-select">
      <option value="male" <?= $user['gender'] === 'male' ? 'selected' : '' ?>>Man</option>
      <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Woman</option>
    </select>

    <button type="submit" class="save-button">Save</button>
  </form>
</div>

</body>
</html>