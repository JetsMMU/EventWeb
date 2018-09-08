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
  $sql = 'SELECT event.id, event.name, event.date, event.time, event.description, event.price, user.name AS organizer
  FROM event
  INNER JOIN user ON event.user_id = user.id
  ORDER BY event.date desc';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $events = $stmt->fetchAll();

  // Array to store all of the contents in cart
  $cartContents = [];

  foreach ($events as $event) {
      array_push($cartContents, $event);
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
            <h3>My Cart</h3>
            <table style="width:100%">
            <tr>
              <th>Select Event</th>
              <th>Event Name</th> 
              <th>Event Description</th>
              <th>Price</th>
            </tr>
            <?php foreach ($cartContents as $event) { ?>
            <tr>
              <td><input type="checkbox" id="<?php echo $event['id'];?>" onclick="getTotal(<?php echo $event['price']; ?>, <?php echo $event['id']; ?>)"></td>
              <td><?php echo $event['name'];?></td> 
              <td><?php echo $event['description']; ?></td>
              <td><?php echo $event['price']; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td></td>
              <td></td> 
              <td align = "right">Total: </td>
              <td id = "pepe"></td>
            </tr>
            </table>
            <td><button id="removeButton" type="button" class="btn btn-default" disabled>Remove</button></td>
            <td><button type="button" class="btn btn-default">Checkout</button></td>
        </div>		
      </div>
		</div>
	</div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>

  <script>
    var total = 0;
    function getTotal($a, $b) {
      var checkBox = document.getElementById($b);
      if (checkBox.checked == true){
        total = total + $a;
      } else {
        total = total - $a;
      }
      document.getElementById("pepe").innerHTML = total;
    }
  </script>
</body>
</html>