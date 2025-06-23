<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
  <link rel="stylesheet" href="../styles/login.css">
  <link rel="stylesheet" href="../styles/header.css" />
</head>
<body>

<?php include 'header.php'; ?>

<div class="profile-container">
  <h1>Login</h1>

  <?php if (isset($_GET['error'])): ?>
    <div class="error-message">
      <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
  <?php endif; ?>


  
  <form action="../backend/do_login.php" method="post">
    <input type="hidden" name="redirect_url" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">

    <div class="input-group">
      <label for="email">Gmail</label>
      <input type="email" id="email" name="email" required>
    </div>

    <label for="password">Password</label>
    <div class="password-wrapper">
      <input type="password" id="password" name="password" required>
      <span class="material-symbols-outlined toggle-password" onclick="togglePassword('password', this)">visibility</span>
    </div>


    <button type="submit" class="save-button">Login</button>
  </form>

  <p class="reg-p">
    Don't have an account? <a href="register.php">Create one here</a>
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
