<?php
session_start();
include 'db_connection.php';  

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$birthdate = $_POST['birthdate'];
$weight = $_POST['weight'];
$height = $_POST['height'];
$gender = $_POST['gender'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$check_sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['error'] = "Користувач з таким email вже існує.";
    header("Location: ../includes/register.php");
    exit();
}

$stmt->close();

$sql = "INSERT INTO users (name, email, password, birthdate, weight, height, gender)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $name, $email, $hashed_password, $birthdate, $weight, $height, $gender);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;

    header("Location: ../includes/profile.php");
    exit();
} else {
    echo "Помилка при збереженні: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
