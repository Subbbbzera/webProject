<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
  <link rel="stylesheet" href="../styles/header.css" />
  <link rel="stylesheet" href="../styles/login.css" />
  
</head>
<body>

<?php include 'header.php'; ?>

<div class="profile-container">
  <h1>Register</h1>

  <?php
  if (isset($_SESSION['error'])) {
      echo "<p style='color: red; text-align: center;'>" . $_SESSION['error'] . "</p>";
      unset($_SESSION['error']);
  }
  ?>

  <form action="../backend/save_register.php" method="post">
    <input type="hidden" name="redirect_url" value="<?php echo $_SERVER['HTTP_REFERER'] ?? ''; ?>">

    <label for="name">Name</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Gmail</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password</label>
    <div class="password-wrapper">
      <input type="password" id="password" name="password" required>
      <span class="material-symbols-outlined toggle-password" onclick="togglePassword('password', this)">visibility</span>
    </div>


    <label for="birthdate">Birthdate</label>
    <input type="date" id="birthdate" name="birthdate" required>

    <div class="row-inputs">
      <div class="input-group">
        <label for="weight">Weight</label>
        <input type="text" id="weight" name="weight" required>
      </div>

      <div class="input-group">
        <label for="height">Height</label>
        <input type="text" id="height" name="height" required>
      </div>
    </div>

    <label for="gender">Gender</label>
    <select id="gender" name="gender" class="gender-select" required>
      <option value="">Select Gender</option>
      <option value="male">Man</option>
      <option value="female">Woman</option>
    </select>

    <button type="submit" class="save-button">Create Account</button>
  </form>

  <p class="reg-p">
    Already have an account? <a href="login.php">Login here</a>
  </p>
</div>

<script>
function togglePassword(inputId, icon) {
  const input = document.getElementById(inputId);
  const isPassword = input.type === "password";
  input.type = isPassword ? "text" : "password";
  icon.textContent = isPassword ? "visibility_off" : "visibility";
}
</script>


</body>
</html>
