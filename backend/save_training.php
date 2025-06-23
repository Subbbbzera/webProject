<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $training_date = $_POST['training_date'];
    $exercise_names = $_POST['exercise_name'];
    $sets = $_POST['sets'];
    $reps = $_POST['reps'];

    if (count($exercise_names) === count($sets) && count($sets) === count($reps)) {
        for ($i = 0; $i < count($exercise_names); $i++) {
            $exercise_name = $conn->real_escape_string($exercise_names[$i]);
            $set = intval($sets[$i]);
            $rep_string = $conn->real_escape_string($reps[$i]);

            $stmt = $conn->prepare("INSERT INTO training_log (user_id, training_date, exercise_name, sets, reps) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issis", $user_id, $training_date, $exercise_name, $set, $rep_string);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: /WebProject/includes/training.php?success=1");
        exit;
    } else {
        echo "Помилка: кількість полів не співпадає.";
    }
} else {
    echo "Неправильний метод запиту.";
}
?>
