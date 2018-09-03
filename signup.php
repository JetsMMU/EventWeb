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
  
  // variable declaration
	$username = "";
	$email    = "";
	$errors = array(); 
    
  // Signup
  if (isset($_POST['reg_user'])) {
    // receive all input values from the form
    $username = (isset($_POST['username']) ? $_POST['username'] : null);
    $email = (isset($_POST['email']) ? $_POST['email'] : null);
    $password_1 = (isset($_POST['password_1']) ? $_POST['password_1'] : null);
    $password_2 = (isset($_POST['password_2']) ? $_POST['password_2'] : null);

    // retrieve email information
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $emails = $stmt->fetchAll();
    $count_email = count($emails);

    // retrieve user information 
    $sql = "SELECT * FROM user WHERE name = '$username'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
    $count_user = count($users);

    // form validation: ensure that the form is filled
    if (empty($username)) { 
      array_push($errors, "Username is required"); 
      }
    if (empty($email)) { 
      array_push($errors, "Email is required"); 
      }
    if (empty($password_1)) { 
      array_push($errors, "Password is required"); 
    }
    // check if the user name and email have been registered
    if (($count_email == 0)&&($count_user == 0)){
      //compare password
      if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match.");
      }
      else{
        // register user if there are no errors in the form
        if(count($errors == 0)){
          //encrypt the password before saving in the database
          $password = password_hash($password_1, PASSWORD_DEFAULT);
          $sql = "INSERT INTO user (name, password, email) 
                  VALUES('$username', '$password', '$email')";
          $stmt = $pdo->prepare($sql);
          $stmt->execute();

          //redirect to login page
          header('location: login.php');
        }
      }
    }
    else{
      array_push($errors, "This user or email address had been registered.");
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
      <?php include('errors.php');?>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" pattern="[^\s]*" title="Username cannot contain space" required autocomplete="off" autofocus>
      </div>
      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="Email must follow characters@characters.domain. Ex. sample@mail.com" required autocomplete="off">
      </div>
      <div class="form-group">
        <label for="password_1">Password</label>
        <input type="password" class="form-control" id="password_1" name="password_1" placeholder="Enter password" pattern=".{6,}" title="Password need to be 6 or more characters" required>
      </div>
      <div class="form-group">
        <label for="password_2">Confirm Password</label>
        <input type="password" class="form-control" id="password_2" name="password_2" placeholder="Confirm password" pattern=".{6,}" title="Password need to be 6 or more characters" required>
      </div>
      <button type="submit" class="btn btn-primary" name="reg_user">Sign Up</button>
    </form>
  </div>

  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>