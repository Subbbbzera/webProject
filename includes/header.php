<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<header>
  <nav class="navbar">
    <div class="nav-left">
      <a href="/WebProject/includes/home.php" class="logo">TrainTrack</a>
    </div>
    
    <ul class="nav-center">
      <li class="nav-item dropdown <?= in_array($currentPage, ['training.php', 'Workoutdiary.php', 'goals.php']) ? 'active' : '' ?>">
        <a href="/WebProject/includes/training.php" onclick="requireAuth(event)">WORKOUTS</a>
        <ul class="dropdown-menu">
          <li><a href="/WebProject/includes/training.php" onclick="requireAuth(event)">Training</a></li>
          <li><a href="/WebProject/includes/Workoutdiary.php" onclick="requireAuth(event)">Workout diary</a></li>
          <li><a href="/WebProject/includes/goals.php" onclick="requireAuth(event)">Goals</a></li>
        </ul>
      </li>
      <li class="nav-item <?= in_array($currentPage, ['maps.php', 'place_details.php', 'newmaps.php', 'all_place.php']) ? 'active' : '' ?>">
        <a href="/WebProject/includes/maps.php" onclick="requireAuth(event)">MAPS</a>
      </li>

      <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item <?= $currentPage === 'profile.php' ? 'active' : '' ?>">
          <a href="/WebProject/includes/profile.php">PROFILE</a>
        </li>
      <?php endif; ?>
    </ul>

    <div class="nav-right">
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="profile-dropdown">
          <a href="#" class="profile-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Profile">
          </a>
          <ul class="dropdown-menu profile-menu">
            <li><a href="/WebProject/includes/profile.php">Profile</a></li>
            <li><a href="/WebProject/includes/logout.php">Log Out</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="/WebProject/includes/login.php" class="btn">LOG IN</a>
        <a href="/WebProject/includes/register.php" class="btn">SIGN UP</a>
      <?php endif; ?>
    </div>
  </nav>
</header>

<script>
  const isAuthenticated = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

  function requireAuth(event, redirectTo) {
    if (!isAuthenticated) {
      event.preventDefault();
      window.location.href = "/WebProject/includes/register.php";
    }
  }
</script>
