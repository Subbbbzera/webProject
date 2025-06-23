<?php
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "workout_app"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id']; 

        if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
            header('Location: ' . $_POST['redirect_url']);
            exit;
        } else {
            header('Location: home.php');
            exit;
        }
    } else {
        header('Location: ../includes/login.php?error=Invalid email or password');
        exit;
    }
} else {
    header('Location: ../includes/login.php?error=Invalid email or password');
    exit;
}

$conn->close();
?>
