<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workout_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Не вдається з'єднатися - " . $conn->connect_error);
}
?>
