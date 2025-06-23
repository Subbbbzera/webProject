<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $birthdate = $_POST['birthdate'];
    $email = trim($_POST['email']);
    $weight = floatval($_POST['weight']);
    $height = floatval($_POST['height']);
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("UPDATE users SET name=?, birthdate=?, email=?, weight=?, height=?, gender=? WHERE id=?");
    $stmt->bind_param("sssddsi", $name, $birthdate, $email, $weight, $height, $gender, $user_id);

    if ($stmt->execute()) {
        header("Location: ../includes/profile.php?success=1");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();