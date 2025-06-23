<?php
session_start();
require_once '../backend/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT training_date, exercise_name, sets, reps 
        FROM training_log 
        WHERE user_id = ? 
        ORDER BY training_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$workouts = [];
while ($row = $result->fetch_assoc()) {
    $date = $row['training_date'];
    $workouts[$date][] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Workout Diary</title>
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/workoutdiary.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="diary-grid">
    <h1>Your Workout Diary</h1>

    <?php if (empty($workouts)): ?>
        <p>No training records found.</p>
    <?php else: ?>
        <div class="workout-grid">
            <?php foreach ($workouts as $date => $entries): ?>
                <div class="workout-card">
                    <div class="workout-date"><?= htmlspecialchars($date) ?></div>
                    <div class="workout-details">
                        <?php foreach ($entries as $entry): ?>
                            <?php
                                $exercise = htmlspecialchars($entry['exercise_name']);
                                $sets = (int)$entry['sets'];
                                $reps_raw = $entry['reps'];

                                // Масив повторів
                                $reps_array = array_map('intval', explode(',', $reps_raw));
                                $total_reps = array_sum($reps_array);

                                // Якщо вказано лише 1 значення, множимо
                                if (count($reps_array) === 1 && $sets > 1) {
                                    $reps_array = array_fill(0, $sets, $reps_array[0]);
                                    $total_reps = array_sum($reps_array);
                                }
                            ?>
                            <div class="exercise-line">
                                <strong><?= $exercise ?></strong><br>
                                <?= implode(', ', $reps_array) ?> reps — Total: <?= $total_reps ?> reps
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


</body>
</html>
