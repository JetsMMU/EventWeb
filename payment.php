<?php
	session_start();

$config = require('config.php');
  $dsn = $config['connection'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
  try {
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
  
  // Retrieve events and their organizers
  $sql = 'SELECT event.name, event.date, event.time, user.name AS organizer
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
  <div class="container text-left">
    <div class="row">
      <div class="col-md-6">
        <p><h2>Please select your payment method</h2></p>
      </div>
    </div>
  </div>

<div class="container text-left">
    <div class="row">
    <div class="col-md-2">
      <figure class="figure" align="center">
        <img src="/EventWeb/src/img/maybank.png" class="figure-img img-fluid rounded" alt="A generic square placeholder image with rounded corners in a figure.">
        <figcaption class="figure-caption">Maybank2u.</figcaption>
      
      </figure>
    </div>
    
    <div class="col-md-2">
      <figure class="figure" align="center">
        <img src="/EventWeb/src/img/cimbclick.png" class="figure-img img-fluid rounded" class="center" alt="A generic square placeholder image with rounded corners in a figure.">
        <figcaption class="figure-caption"; >CIMB Clicks.</figcaption>
      </figure>
    </div>
    
    <div class="col-md-2">
      <figure class="figure" align="center">
        <img src="/EventWeb/src/img/visa.png" class="figure-img img-fluid rounded" class="center" alt="A generic square placeholder image with rounded corners in a figure.">
        <figcaption class="figure-caption"; >Visa.</figcaption>
      </figure>
    </div>

    <div class="col-md-2" >
      <figure class="figure" align="center" >
        <img src="/EventWeb/src/img/mastercard.png" class="figure-img img-fluid rounded" class="center" alt="A generic square placeholder image with rounded corners in a figure.">
        <figcaption class="figure-caption"; >Master Card.</figcaption>
      </figure>
    </div>
  </div>
</div>

<div class="container text-left">
    <div class="tab-pane" id="past">
            <h3>Event Description</h3>
            <div class="row">
              <?php foreach ($pastEvents as $event) { ?>
                <div class="col-sm-4 card" data-toggle="modal" data-target="#eventModal">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $event['name']; ?></h5>
                    <p>By: <?php echo $event['organizer']; ?></p>
                    <p>Date: <?php echo $event['date']; ?></p>
                    <p>Time: <?php echo $event['time']; ?></p>
                  </div> 
                </div>
              <?php } ?>
            </div>
          </div>          
        </div>    
      </div>
</div>

<div class="container text-right">
<div class="col-sm-12">
                    <h3>Total Payment: </h4>
                    <h4>RM100</h4>
                    
                  </div>
                </div>
</div>
</div>
  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>