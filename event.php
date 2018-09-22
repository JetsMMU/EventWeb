<?php
  session_start();
  
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
  
  // Retrieve events and their organizers
  $sql = 'SELECT event.id, event.user_id, event.name, event.date, event.time, event.price, user.name AS organizer
  FROM event
  INNER JOIN user ON event.user_id = user.id
  ORDER BY event.date desc';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $events = $stmt->fetchAll();

  // Separate events into upcoming and past
  $upcomingEvents = [];
  $pastEvents = [];

  foreach ($events as $event) {
    // Convert 00:00:00 to 00:00 AM/PM
    $event['time'] = date('h:i A', strtotime($event['time']));

    // Check whether event's datetime >= today 
    if (date('Y-m-d H:i:s', strtotime($event['date'] . ' ' . $event['time'])) >= date('Y-m-d H:i:s')) {
      array_push($upcomingEvents, $event);
    } else {
      array_push($pastEvents, $event);
    }
  }

  $sql = 'SELECT participation.event_id, participation.user_id, event.name AS eventname, user.name AS username FROM participation 
  INNER JOIN event ON event.id = participation.event_id
  INNER JOIN user ON user.id = participation.user_id';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $participations = $stmt->fetchAll();

  $participationList = [];

  foreach ($participations as $participation) {
    array_push($participationList, $participation);
  }

  $sql = 'SELECT id FROM user where name = "' . $_SESSION['username'] . '"';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $curID = $stmt->fetchColumn();

  $sql = 'SELECT * FROM cart';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $carts = $stmt->fetchAll();

  $cartList = [];

  foreach ($carts as $cart) {
    array_push($cartList, $cart);
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
  <br>

  <!-- body -->
  <div class="container">
    <div class="row">
      <div class="col">
        <?php if (isset($_SESSION['eventAttendanceMessage'])) { ?>
          <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> <?php echo $_SESSION['eventAttendanceMessage']; ?>
          </div>
        <?php } ?>
        <?php unset($_SESSION['eventAttendanceMessage']); ?>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <ul class="nav nav-tabs nav-stacked">
          <li class="nav-item">
            <a href="#upcoming" class="nav-link active" data-toggle="tab">Upcoming Events</a>
          </li>
          <li class="nav-item">
            <a href="#past" class="nav-link" data-toggle="tab">Past Events</a>
          </li>
        </ul>
    
        <div class="tab-content">
          <div class="tab-pane active" id="upcoming">
            <h3>Upcoming Events</h3>
            <div class="row">
              <?php foreach ($upcomingEvents as $event) { ?>
                <div class="col-sm-4 card" data-toggle="modal" data-target="#eventModal" onclick='getOrg("<?php echo $_SESSION['username']; ?>", "<?php echo $event['organizer']; ?>", "<?php echo $curID ?>", "<?php echo $event['id']; ?>" , <?php echo json_encode($participationList); ?>, <?php echo json_encode($cartList); ?>)'>
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $event['name']; ?></h5>
                    <p>By: <?php echo $event['organizer']; ?></p>
                    <p>Date: <?php echo $event['date']; ?></p>
                    <p>Time: <?php echo $event['time']; ?></p>
                    <p>Price: <?php echo $event['price']; ?></p>
                    Participants:
                    <p>
                    <?php $temp = 0; foreach ($participationList as $participant) { if($event['id'] == $participant['event_id']) { $temp++; }} echo $temp; ?>
                    </p>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
          <div class="tab-pane" id="past">
            <h3>Past Events</h3>
            <div class="row">
              <?php foreach ($pastEvents as $event) { ?>
                <div class="col-sm-4 card" data-toggle="modal" data-target="#eventModal">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $event['name']; ?></h5>
                    <p>By: <?php echo $event['organizer']; ?></p>
                    <p>Date: <?php echo $event['date']; ?></p>
                    <p>Time: <?php echo $event['time']; ?></p>
                    <p>Price: <?php echo $event['price']; ?></p>
                    Participants:
                    <p>
                    <?php $temp = 0; foreach ($participationList as $participant) { if($event['id'] == $participant['event_id']) { $temp++; }} echo $temp; ?>
                    </p>
                  </div> 
                </div>
              <?php } ?>
            </div>
          </div>	        
        </div>		
      </div>
		</div>
	</div>

	<div id="eventModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
          <h4 id="EventTitle" class="modal-title"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body">
          <p>By: <span id="EventOrganizer" class="event-organizer"></span></p>
					<p>Time: <span class="event-time"></span></p>
					<p>Date: <span class="event-date"></span></p>
					<p>Venue: <span class="event-venue"></span></p>
					<p>Description: <span class="event-description"></span></p>
				</div>

				<div class="modal-footer">
					<form method="POST" action="api.php">
						<?php if (isset($_SESSION['username'])) { ?>
							<input type="hidden" name="eventName" class="input-event-name">
							<input type="hidden" name="eventAttendance" class="input-attendance">
              <input id = GoingButton type="submit" name="eventAttendee" value="Add to Cart" class="btn going-btn">
						<?php } else { ?>
							<a href="/EventWeb/login.php" class="btn btn-default" role="button">Going</a>
						<?php } ?>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</form>
				</div>
			</div>
		</div>
	</div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>