<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require('partials/html-head.php'); ?>
</head>
<body>
  <!-- header -->
  <?php require('partials/header.php'); ?>

  <!-- body -->
  <div class="container text-center">
    <h1>Payment Successful</h1>
    <a href="home.php"><button type="submit" class="btn btn-default">Return to Home</button></a>
  </div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>