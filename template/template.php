<?php
  session_start();
  
  // Redirect guest to login page
  /**
   * Use these lines only if the file does not allow guest(non-registered user) to access  
   */
	// if (!isset($_SESSION['username'])) {
	// 	header("location: login.php");
	// }

  // Connect to database
  /**
   * Use these lines only if the file requires database connection. 
   */
	// $config = require('config.php');
	// $dsn = $config['connection'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
	// try {
	// 	$pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
	// } catch (PDOException $e) {
	// 	die($e->getMessage());
	// }
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
  

  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>