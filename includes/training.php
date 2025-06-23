<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Training Log</title>
  <link rel="stylesheet" href="../styles/header.css" />
  <link rel="stylesheet" href="../styles/training.css" />
</head>
<body>

<?php include 'header.php'; ?>

<div class="training-container">
  <h1>Your training day</h1>

  <form action="../backend/save_training.php" method="post">
  <label for="training-date">Training Date:</label>
  <input type="date" id="training-date" name="training_date" required>

  <div id="exercises-container">
    <div class="exercise-entry">
      <input type="text" name="exercise_name[]" placeholder="Exercise name" required>
      <input type="number" name="sets[]" placeholder="Sets" required>
      <input type="text" name="reps[]" placeholder="Reps per set" required>
    </div>
  </div>

  <button type="button" id="add-exercise">+ Add Exercise</button>
  <button type="submit" class="save-button">Save Training</button>
</form>

</div>

<script>
document.getElementById('add-exercise').addEventListener('click', function() {
  const container = document.getElementById('exercises-container');
  const newEntry = document.createElement('div');
  newEntry.className = 'exercise-entry';
  newEntry.innerHTML = `
    <input type="text" name="exercise_name[]" placeholder="Exercise name" required>
    <input type="number" name="sets[]" placeholder="Sets" required> 
    <input type="text" name="reps[]" placeholder="Reps per set" required>
  `;
  container.appendChild(newEntry);
});
</script>

</body>
</html>
