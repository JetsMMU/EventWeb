<?php
	session_start();

  $config = require('config.php');
  $dsn = $config['connection'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
  try {
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
  
  $currentuser = $_SESSION['username'];
  // Retrieve events and their organizers
  $sql = "SELECT event.name, event.description, event.date, event.time, event.price, cart.id, cart.user_id
  FROM event 
  INNER JOIN cart ON cart.id = event.id
  INNER JOIN user ON cart.user_id = user.id WHERE user.name = '$currentuser' ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $events = $stmt->fetchAll();

  // Array to store all of the contents in cart
  $cartContents = [];
  $totalprice = [];
  $cartid = [];

  foreach ($events as $event) {
      array_push($cartContents, $event);
  }

  foreach ($events as $event) {
      array_push($totalprice, $event{"price"});
  }

  foreach ($events as $event) {
      array_push($cartid, $event{"id"});
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

 
<div class="container text-left" >
    
    <div class="row">
    <div id="opt1" class="col-md-2" onclick="AddBorder(1);">
     <figure  class="figure" align="center" onclick="addborder();" >
        <img src="/EventWeb/src/img/maybank.png" class="figure-img img-fluid rounded" alt="A generic square placeholder image with rounded corners in a figure.">

        <figcaption class="figure-caption">Maybank2u.</figcaption>
      
    </figure>
      
    </div>


    <div id="opt2" class="col-md-2" onclick="AddBorder(2);">
      <figure class="figure" align="center">
        <img src="/EventWeb/src/img/cimbclick.png" class="figure-img img-fluid rounded" class="center" alt="A generic square placeholder image with rounded corners in a figure.">
        <figcaption class="figure-caption"; >CIMB Clicks.</figcaption>
      </figure>
    </div>
    
    <div id="opt3" class="col-md-2" onclick="AddBorder(3);">
      <figure class="figure" align="center">
        <img src="/EventWeb/src/img/visa.png" class="figure-img img-fluid rounded" class="center" alt="A generic square placeholder image with rounded corners in a figure.">
        <figcaption class="figure-caption"; >Visa.</figcaption>
      </figure>
    </div>

    <div id="opt4" class="col-md-2" onclick="AddBorder(4);">
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

            <?php foreach ($cartContents as $event) { ?>
            <div class="col-sm-4 card" data-toggle="modal" data-target="#eventModal">
              <div class="card-body">
                <p><h5>Event Name:</h5> <?php echo $event['name'];?></p> 
                <p><h5>Date:</h5> <?php echo $event['date'];?></p> 
                <p><h5>Time:</h5> <?php echo $event['time'];?></p> 
                <p><h5>Description:</h5> <?php echo $event['description']; ?></p>
                <p><h5>Price:</h5> <?php echo $event['price']; ?></p>
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
                    <h4><?php echo array_sum($totalprice);?></h4>

</div>
</div>


  <div class="container text-right">
    <div class="row">
    <div class="col-sm-11">
    <form method="POST" action="api.php">
      <button id="submission" type="submit" class="btn btn-default" disabled>Submit
        <input type="hidden" name="hello" value=" <?php echo $event{"user_id"}; ?>"/> 
      </button>
    </form>
    </div>
    <a href="/EventWeb/cart.php"><button type="cancel" class="btn btn-default">Cancel</button></a>
  </div>
</div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>