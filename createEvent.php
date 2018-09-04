<?php
  session_start();
  
  // Redirect guest to login page
  /**
   * Use these lines only if the file does not allow guest(non-registered user) to access  
   */
	if (!isset($_SESSION['username'])) {
		header("location: login.php");
	}

  // Connect to database
  /**
   * Use these lines only if the file requires database connection. 
   */
	$config = require('config.php');
	$dsn = $config['connection'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
	try {
		$pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
	} catch (PDOException $e) {
		die($e->getMessage());
	}
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
  <div class="container">
		<?php if (isset($_SESSION['createEventMessage'])) { ?>
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Success!</strong> <?php echo $_SESSION['createEventMessage']; ?>
			</div>
		<?php } ?>
		<?php unset($_SESSION['createEventMessage']); ?>

		<h1>Create Event</h1>
		
		<form method="POST" action="/api.php">
			<div class="form-group">
				<label for="eventName">Event Name: </label>
				<input type="text" name="eventName" class="form-control" id="eventName">
			</div>
			<div class="form-group">
				<label for="eventDescription">Event Description: </label>
				<textarea class="form-control" rows="5" name="eventDescription" id="eventDescription"></textarea>
			</div>
			<div class="form-group">
				<label for="eventDate">Event Date: </label>
				<input type="date" name="eventDate" class="form-control" id="eventDate">
			</div>
			<div class="form-group">
				<label for="eventTime">Event Time: </label>
				<input type="time" name="eventTime" class="form-control" id="eventTime">
			</div>
			<div class="form-group">
				<label for="eventVenue">Event Venue: </label>
				<input type="text" name="eventVenue" class="form-control" id="eventVenue">
			</div>
			<button type="submit" class="btn btn-default">Create</button>
		</form>
	</div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>