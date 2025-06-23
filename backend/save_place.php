<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "Користувач не автентифікований.";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO places (name, description, latitude, longitude, is_public, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddii", $name, $description, $latitude, $longitude, $is_public, $user_id);

    if ($stmt->execute()) {
        header("Location: ../includes/maps.php");
        exit();
    } else {
        echo "Помилка: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Недійсний запит.";
}
?>
