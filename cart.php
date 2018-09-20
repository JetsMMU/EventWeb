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
  $sql = 'SELECT cart.id, event.name, event.description, event.price FROM cart INNER JOIN event ON cart.id = event.id';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $carts = $stmt->fetchAll();

  // Array to store all of the contents in cart
  $cartContents = [];

  foreach ($carts as $cart) {
      array_push($cartContents, $cart);
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
            <?php foreach ($cartContents as $cart) { ?>
            <tr>
              <td><input type="checkbox" id="<?php echo $cart['id'];?>" onclick="getTotal(<?php echo $cart['price']; ?>, <?php echo $cart['id']; ?>)"></td>
              <td><?php echo $cart['name'];?></td> 
              <td><?php echo $cart['description']; ?></td>
              <td><?php echo $cart['price']; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td></td>
              <td></td> 
              <td align = "right">Total: </td>
              <td id = "totalprice">0.00</td>
            </tr>
            </table>
            <div align="right">
            <td><button id="removeButton" type="button" class="btn btn-default" disabled>Remove</button></td>
            <td><button id="checkoutButton" type="button" class="btn btn-default" disabled>Checkout</button></td>
          </div>
        </div>		
      </div>
		</div>
	</div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>

  <script>
    var total = 0;
    var checkboxes = 0;
    function getTotal($a, $b) {
      var checkBox = document.getElementById($b);
      if (checkBox.checked == true){
        total = total + $a;
        checkboxes++;
      } else {
        total = total - $a;
        checkboxes--;
      }
      document.getElementById("totalprice").innerHTML = total.toFixed(2);
      if(checkboxes > 0)
      {
        $("#removeButton").attr("disabled",false);
        $("#checkoutButton").attr("disabled",false);
      }
      else
      {
        $("#removeButton").attr("disabled",true);
        $("#checkoutButton").attr("disabled",true);
      }
    }
  </script>
</body>
</html>