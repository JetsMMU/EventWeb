<?php 
session_start();
    // Redirect guest to login page
if (!isset($_SESSION['username'])) {
  header("location: login.php");
}

    // Connect to database
$config = require('config.php');
$dsn = $config['connection'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
try {
  $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
} catch (PDOException $e) {
  die($e->getMessage());
}

    // variable declaration
$newpassword = "";
$errors = array();
$success = array();

if (isset($_POST['submitPassword'])) {
  $newpassword = (isset($_POST['password']) ? $_POST['password'] : null);

  if (empty($newpassword)) {
    array_push($errors, "Password is required");
  } else {
            // update in database
    $password = password_hash($newpassword, PASSWORD_DEFAULT);
    $username = $_SESSION['username'];
    $sql = "UPDATE user
                    SET password = '$password'
                    WHERE name='$username'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    array_push($success, "Password is changed successfully. Please click <a class=\"form_footer_reg\" href=\"profile.php\"> here </a> to view profile.");
  }
}
?>

<!DOCTYPE html>
<html>
<head>
	<?php require('partials/html-head.php'); ?>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>
<body class="changePass">
  <!-- header -->
  <?php require('partials/header.php'); ?>
  <br>

  <!-- body -->
  <div class="container changeContainer">
    <div class="row">
      <div class="col">
        <h2>Enter a New Password</h2>
        <?php echo !empty($statusMsg) ? '<p class="' . $statusMsgType . '">' . $statusMsg . '</p>' : ''; ?>
        <form method="post">
        <!-- display validation message -->
          <?php if (count($errors) > 0) : ?>
            <div class="error-fp">
              <?php foreach ($errors as $error) : ?>
                <p><?php echo $error; ?></p>
              <?php endforeach ?>
            </div>
          <?php endif ?>

          <?php if (count($success) > 0) : ?>
            <div class="success-fp">
              <?php foreach ($success as $sc) : ?>
                <p><?php echo $sc; ?></p>
              <?php endforeach ?>
            </div>
          <?php endif ?>

          <div class="container changeForm">
            <input type="password" name="password" pattern=".{6,}" title="Password need to be 6 or more characters" required autocomplete="off" autofocus>
            <button class="unmask btn btn-default" type="button" title="Mask/Unmask password to check content"><i class="fas fa-eye-slash"></i></button>
          </div>
          <input type="submit" name="submitPassword" class="btn btn-primary" value="Submit">
        </form>  
      </div>
    </div>
  </div>

  <?php require('partials/footer.php'); ?>
  <script src="js/script.js"></script>
</body>
</html>