<?php
session_start();
//$_SESSION['username'] = 'john'; // Temporary hardcoded

// Connect to database
$config = require('config.php');
$dsn = $config['connection'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
try {
	$pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
} catch (PDOException $e) {
	die($e->getMessage());
}
//testing 
// for dashboard.php
if (isset($_POST['eventAttendees']) && $_POST['eventAttendees'] && isset($_POST['eventName'])) {
    $sql = 'SELECT user.name 
            FROM participation 
            INNER JOIN user ON participation.user_id = user.id
            WHERE event_id = (SELECT id FROM event WHERE name = :name)';
    $stmt = $pdo->prepare($sql);
	$stmt->execute(['name' => $_POST['eventName']]);
	$attendees = $stmt->fetchAll();
    
    if ($attendees) {
		echo json_encode($attendees);	
	} else {
		echo json_encode("ERROR: No such attendees/event.");
	}
}

// for event.php
else if (isset($_GET['eventName'])) {
	// Retrieve event details based on event name
	$sql = 'SELECT event.id, event.description, event.date, event.time, event.venue, user.name AS organizer
		FROM event
		INNER JOIN user ON event.user_id = user.id
		WHERE event.name = :event';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['event' => $_GET['eventName']]);
	$event = $stmt->fetch();
	// Send response to client
	if ($event) {
		// Check if user is attending the event
		if (isset($_SESSION['username'])) {
			$sql = 'SELECT 1 
				FROM participation 
				WHERE event_id = :event_id AND user_id = (SELECT id FROM user WHERE name = :name)';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['event_id' => $event['id'], 'name' => $_SESSION['username']]);
			$attendance = $stmt->fetch();
			$event['attendance'] = !empty($attendance);
			unset($event['id']);
		}
		echo json_encode($event);	
	} else {
		echo json_encode("ERROR: No such event.");
	}
} 
else if (isset($_POST['eventName']) && isset($_POST['eventAttendance'])) {
	if($_POST['eventAttendee'] == 'Add to Cart') {
		// $sql = 'INSERT INTO participation (event_id, user_id)
		// 	VALUES ((SELECT id FROM event WHERE name = "' . $_POST['eventName'] . '"),
		// 	(SELECT id FROM user WHERE name = "' . $_SESSION['username']  . '"))';
		$sql = 'INSERT INTO cart (id, user_id)
		VALUES ((SELECT id FROM user WHERE name = :username), (SELECT id FROM event WHERE name = :name))';
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'username' => $_SESSION['username'],
			'name' => $_POST['eventName']
		]);
		$_SESSION['eventAttendanceMessage'] =  $_POST['eventName'] . ' has been added to the cart.';
	}
	else { // Undefined behaviour
		die($_POST['eventAttendance']);
	}
	// if ($_POST['eventAttendance'] === 'true') { // User doesn't want to attend, remove row
	// $_SESSION['eventAttendanceMessage'] = 'You have choose to not attend ' . $_POST['eventName'] . '.';
	// $sql = 'DELETE FROM participation
	// 	WHERE event_id = (SELECT id FROM event WHERE name = :eventname)
	// 	AND user_id = (SELECT id FROM user WHERE name = :username)';
	// } else if ($_POST['eventAttendance'] === 'false') { // User wants to attend, insert row
	// 	$_SESSION['eventAttendanceMessage'] = 'You have choose to attend ' . $_POST['eventName'] . '.';
	// 	$sql = 'INSERT INTO participation (event_id, user_id)
	// 		VALUES ((SELECT id FROM event WHERE name = :eventname),
	// 		(SELECT id FROM user WHERE name = :username))';
	// } else { // Undefined behaviour
	// 	die($_POST['eventAttendance']);
	// }
	// $stmt->execute(['eventname' => $_POST['eventName'], 'username' => $_SESSION['username']]);
	header('location: event.php');
}

// for create-event.php
else if (isset($_POST['eventName']) && isset($_POST['eventDescription']) && isset($_POST['eventDate']) && isset($_POST['eventTime']) && isset($_POST['eventVenue']) && isset($_POST['eventPrice'])) {
	$sql = 'INSERT INTO event (name, description, date, time, venue, user_id, price)
		VALUES (:name, :description, :date, :time, :venue, (SELECT id FROM user WHERE name = :username), :price)';
	$stmt = $pdo->prepare($sql);
	$stmt->execute([
		'name' => $_POST['eventName'],
		'description' => $_POST['eventDescription'],
		'date' => date("Y-m-d", strtotime($_POST['eventDate'])),
		'time' => $_POST['eventTime'],
		'venue' => $_POST['eventVenue'],
		'username' => $_SESSION['username'],
		'price' => $_POST['eventPrice']
	]);
	$sql = 'INSERT INTO participation VALUES ((SELECT max(id) from event), (SELECT user_id FROM event WHERE id = (select max(id) from event)))';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$_SESSION['createEventMessage'] = $_POST['eventName'] . ' is created.';
	header('location: createEvent.php');
}

else if(isset($_POST['hello'])) {
	$todelete = (int)$_POST['hello'];
	$sql = "DELETE FROM cart where cart.user_id = $todelete ";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	header('location: PaymentSuccess.php');
}
// for undefined behaviour
else {
	header('location: home.php');
}