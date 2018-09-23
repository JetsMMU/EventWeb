<?php
session_start();
  
  // Redirect guest to login page
/**
 * Use these lines only if the file does not allow guest(non-registered user) to access  
 */
if (!isset($_SESSION['username'])) {
  header("location: login.php");
}

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
  
  // Retrieve user details 
$sql = 'SELECT *
  FROM user
  WHERE user.id = (SELECT id FROM user WHERE name = :name)';
$stmt = $pdo->prepare($sql);
$stmt->execute(['name' => $_SESSION['username']]);
$user = $stmt->fetchAll();
$thisuser = $user[0]['name'];
$thisname = $user[0]['full_name'];
$thisemail = $user[0]['email'];
$thisdob = $user[0]['dob'];
$thisgender = $user[0]['gender'];
$thisoccupation = $user[0]['occupation'];
$thisphone = $user[0]['phone'];
$thisdescription = $user[0]['description'];

$success = array();

if (isset($_POST['update'])) {
  $new_name = (isset($_POST['full_name']) ? $_POST['full_name'] : null);
  $new_dob = (isset($_POST['dob']) ? $_POST['dob'] : null);
  if ($thisgender == null) {
    $new_gender = (isset($_POST['Gender']) ? $_POST['Gender'] : null);
  }
  $new_phone = (isset($_POST['phone_number']) ? $_POST['phone_number'] : null);
  $new_occupation = (isset($_POST['Occupation']) ? $_POST['Occupation'] : null);
  $new_description = (isset($_POST['description']) ? $_POST['description'] : null);

  if ($thisgender == null) {
    $sql = "UPDATE user
        SET full_name = '$new_name',
        dob = '$new_dob',
        gender = '$new_gender',
        occupation = '$new_occupation',
        phone = '$new_phone',
        description = '$new_description'
        WHERE name='$thisuser'";
  } else {
    $sql = "UPDATE user
        SET full_name = '$new_name',
        dob = '$new_dob',
        occupation = '$new_occupation',
        phone = '$new_phone',
        description = '$new_description'
        WHERE name='$thisuser'";
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  array_push($success, "The profile is updated successfully.");

    // refresh
  header('location: profile.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require('partials/html-head.php'); ?>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
</head>
<body>
  <!-- header -->
  <?php require('partials/header.php'); ?>
  <br>

  <!-- body -->
  <div class="container text-center" >
    <h1> @<?php echo $thisuser ?> </h1>
  </div>

  <div class="container" >
    <div class="row">
      <div class="col-md-12 ">
        <form method="POST">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="full_name">Name (Full name)</label> 
              <input id="full_name" name="full_name" type="text" placeholder="Name (Full name)" class="form-control" value="<?php echo $thisname ?>">
            </div>

            <div class="form-group col-md-6">
              <label for="dob">Date Of Birth</label>
              <input id="dob" name="dob" type="text" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" placeholder="Date Of Birth (YYYY-MM-DD) Eg. 1998-12-25" class="form-control" value="<?php echo $thisdob ?>">
            </div>
          </div>
          <br>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="Gender">Gender</label>
              <input id="gender" name="Gender_edit" type="text" class="form-control" value="<?php echo $thisgender ?>" disabled>
            </div>

            <div class="form-group col-md-6">
              <label for="Occupation">Occupation</label>
              <input id="Occupation" name="Occupation" type="text" placeholder="Occupation" class="form-control" value="<?php echo $thisoccupation ?>">
            </div>
          </div>
          <br>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="phone_number">Phone number </label>   
              <input id="phone_number" name="phone_number" type="text" placeholder="Phone number (without '-') Eg 0127786767" class="form-control input-md" value="<?php echo $thisphone ?>">
            </div>

            <div class="form-group col-md-6">
              <label for="Email Address">Email Address</label>  
              <input id="Email Address" name="Email Address" type="text" placeholder="Email Address" class="form-control input-md" value="<?php echo $thisemail ?>" disabled>	
            </div>
          </div>
          <br>
          
          <div class="form-group">
            <label for="Change Password">Change Your Password</label>
            <br>
            Click <a href="changePassword.php">here </a> to change password.
          </div>
          <br>

          <!-- Textarea -->
          <div class="form-group">
            <label for="description">Describe yourself (max 200 words)</label>
            <div class="col-md-12">                     
              <textarea class="form-control" rows="10"  id="description" name="description" 
              placeholder="I am an enthusiastic person who is looking forward to provide the best event experience." ><?php echo $thisdescription ?></textarea>
            </div>
          </div>

          <div class="form-group">
            <input type="submit" name="update" class="btn btn-success" value="Update">
          </div>			
        </form>
      </div>
    </div>
  </div>
  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>