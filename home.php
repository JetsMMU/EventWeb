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
    <div class="row">
      <div class="col">
        <div class="jumbotron" style="background-color: #80cbc4;">
          <h1 class="display-4">EventWeb</h1>
          <p class="lead">Make Your Schedule Easy. Best Event Planner for Your Life.</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
          <a href="/EventWeb/event.php"><h3 class="card-title" style="font-weight: lighter;">Browse Events</h3></a>
            <p class="card-text">
              Check Events across Your Interests. Browse through The Most Anticipated Events.
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <a href="/EventWeb/createEvent.php"><h3 class="card-title" style="font-weight: lighter;">Create Events</h3></a>
            <p class="card-text">
              Create Events and Announce Them to the World.
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <a href="#"><h3 class="card-title" style="font-weight: lighter;">Attend Events</h3></a>
            <p class="card-text">
              Check Events that interest you most and Grab the Chance to attend.
            </p>
          </div>
        </div>   
      </div>
    </div>

  </div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>