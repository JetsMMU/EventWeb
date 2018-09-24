<?php 
session_start();

// Connect to database
$config = require('config.php');
$dsn = $config['connection'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
try {
  $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
} catch (PDOException $e) {
  die($e->getMessage());
}

    // variable declaration
$username = "";
$email = "";
$errors = array(); 

    // LOGIN USER
if (isset($_POST['login_user'])) {
        // receive all input from the form
  $username = (isset($_POST['username']) ? $_POST['username'] : null);
  $password = (isset($_POST['password']) ? $_POST['password'] : null);

        // form validation: ensure that the form is filled
  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

        // login user if there are no errors in the form
  if (count($errors) == 0) {
        //get user/password combination from db
    $sql = "SELECT password FROM user WHERE name='$username'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pw = $stmt->fetchAll();
    $count_pw = count($pw);

    if ($count_pw > 0) {
      $hash = $pw['0']['password'];
            //verify password
      if (password_verify($password, $hash)) {
        $_SESSION['username'] = $username;
        header('location: home.php');
      } else {
        array_push($errors, "Wrong username/password combination");
      }
    } else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require('partials/html-head.php'); ?>
    <link rel="stylesheet" href="src/css/login.css" >
</head>
<body>
  <!-- header -->
  <?php require('partials/header.php'); ?>

  <!-- body -->
  <div class="container justify-content-center sp-body">
    <form method="post">
      <?php include('errors.php'); ?>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required autocomplete="off" autofocus>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
      </div>
      <button type="submit" class="btn btn-primary" name="login_user">Log In</button>
    </form>
  </div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>