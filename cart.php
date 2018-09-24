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

$sql = 'UPDATE cart SET activation = 0';
$stmt = $pdo->prepare($sql);
$stmt->execute();

  // Retrieve events and their organizers
$sql = 'SELECT cart.id, cart.user_id, event.name, event.description, event.price FROM cart INNER JOIN event ON cart.id = event.id';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$carts = $stmt->fetchAll();

  // Array to store all of the contents in cart
$cartContents = [];

foreach ($carts as $cart) {
  array_push($cartContents, $cart);
}

$sql = 'SELECT id FROM user where name = "' . $_SESSION['username'] . '"';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$curID = $stmt->fetchColumn();
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
        <?php if (isset($_SESSION['cartMessage'])) { ?>
          <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> <?php echo $_SESSION['cartMessage']; ?>
          </div>
        <?php 
      } ?>
        <?php unset($_SESSION['cartMessage']); ?>
      </div>
    </div>
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
            <?php foreach ($cartContents as $cart) {
              if ($curID == $cart['user_id']) {
                ?>
            <tr>
              <td><input type="checkbox" id="<?php echo $cart['id']; ?>" onclick="getTotal(<?php echo $cart['price']; ?>, <?php echo $cart['id']; ?>)"></td>
              <td><?php echo $cart['name']; ?></td> 
              <td><?php echo $cart['description']; ?></td>
              <td><?php echo $cart['price']; ?></td>
              <td>
                <form method="POST" action="api.php">
                    <input type="hidden" name="eventName" class="input-event-name">
                    <input type="hidden" name = "CartDelete" value = <?php echo $cart['id']; ?> >
                    <td><input id = <?php echo $cart['id']; ?> type="submit" value="Remove" name="removeButton" class="btn btn-default"></td>
                </form>
              </td>
            </tr>
            <?php 
          } ?>
            <?php 
          } ?>
            <tr>
              <td></td>
              <td></td> 
              <td align="right">Total: </td>
              <td id = "totalprice">0.00</td>
            </tr>
            </table>
            <div align="right">
              <div class="modal-footer">
                <form method="POST" action="api.php">
                    <input type="hidden" name="eventName" class="input-event-name">
                    <input id="selectedCart" type="hidden" name="cartList" value = "">
                    <input id="checkoutButton" type="submit" value="Checkout" name="checkoutButton" class="btn btn-default" disabled>
                </form>
              </div>
          </div>
        </div>		
      </div>
		</div>
	</div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>