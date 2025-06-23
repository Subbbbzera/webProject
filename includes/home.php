<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>TrainTrack</title>

  <link rel="stylesheet" href="../styles/header.css" />
  <link rel="stylesheet" href="../styles/home.css" />
  <link rel="stylesheet" href="../styles/footer.css" />
  
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap" rel="stylesheet">

</head>
<body>

<?php include 'header.php'; ?>


<main>
  <div class="firstpage">

      <div class="container">
        <h1>Training without <br> a gym</h1>
        <p class="startext">You can train not only in the <br>gym, but also in the yard, we will <br> help you with this</p>
        <a href="register.php" class="button">Sign up</a>
        <div class="linktolog"> 
          <p>Already have an account? <a href="login.php" class="link-text">Log in</a></p>
        </div>
      </div>

        <div class="image-container">
              <img src="../photo/photo2.jpg" alt="Image 1"  class="progress-image1" width="360" height="325">
        </div>

  </div>

<div class="progress-section">

  <div class="image-container reveal">
    <img src="../photo/photo1.jpg" alt="Image 1" class="page2-image1" width="370" height="260">
    <img src="../photo/Photo3.jpg" alt="Image 3" class="page2-image2" width="290" height="260">
  </div>

  <div class="Textcontainer reveal">
    <h2>Track your progress</h2>
    <p class="midtext">
      Track your sets and results to see real <br> progress, and write down your goals.<br>
      This will help you plan your workouts <br>  better and gradually improve your results<br>
      without being distracted by unimportant things.
    </p>
    <a href="training.php" class="start-button" onclick="requireAuth(event)">Start Now</a>
  </div>

</div>


</main>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const revealElements = document.querySelectorAll('.reveal');

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('active');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.2
    });

    revealElements.forEach(el => observer.observe(el));
  });
</script>

<?php include 'footer.php'; ?>


</body>
</html>
