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

  if (isset($_POST['update'])){
    $new_name = (isset($_POST['full_name']) ? $_POST['full_name'] : null);
    $new_dob = (isset($_POST['dob']) ? $_POST['dob'] : null);
    if($thisgender==null){
      $new_gender = (isset($_POST['Gender']) ? $_POST['Gender'] : null);
    }
    $new_phone = (isset($_POST['phone_number']) ? $_POST['phone_number'] : null);
    $new_occupation = (isset($_POST['Occupation']) ? $_POST['Occupation'] : null);
    $new_description = (isset($_POST['description']) ? $_POST['description'] : null);

    if($thisgender==null){
      $sql = "UPDATE user
        SET full_name = '$new_name',
        dob = '$new_dob',
        gender = '$new_gender',
        occupation = '$new_occupation',
        phone = '$new_phone',
        description = '$new_description'
        WHERE name='$thisuser'";
    }else{
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
  <div class="container text-center" >
      <div class="row">
        <div id="opt1" class="col-md-12" onclick="AddBorder(1);">
          <img src="/EventWeb/src/img/favicon.ico" class="figure-img img-fluid rounded" alt="A generic square placeholder image with rounded corners in a figure.">
      
    
      
        </div>
      </div>
  </div>
  <!-- body -->
  <div class="container text-center" >
  <div class="topBg">
    <!-- <img src="/EventWeb/src/img/favicon.ico" alt="background"> -->
    <h1> @<?php echo $thisuser ?> </h1>
  </div>
</div>
  <div class="middleBg">
    <!-- <h1> @<?php echo $thisuser ?> </h1> -->
  </div>

<div class="container text-center" >
  <div class="container">
    <div class="row">
      <div class="col-md-12 ">
        <form class="form-horizontal" method="POST">
          <fieldset>
            <div class="form-group">
              <label class="col-md-5 control-label" for="full_name">Name (Full name)</label>  
              <div class="col-md-12">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <input id="full_name" name="full_name" type="text" placeholder="Name (Full name)" class="form-control input-md" value="<?php echo $thisname?>">
                </div>	
              </div>
            </div>
            <br>
          <!-- Text input-->
            <div class="form-group">
              <label class="col-md-5 control-label" for="Change Password">Change Your Password</label>
              <div class="col-md-12">
                <div class="profile-padding">
                  <!-- Click <a href="/changePassword.php">here </a> to change password. -->
                  Click <a href="#">here </a> to change password.
                </div>
              </div>
            </div>
            <br>
            <div class="form-group">
              <label class="col-md-5 control-label" for="dob">Date Of Birth</label>  
              <div class="col-md-12">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-birthday-cake"></i>
                  </div>
                  <input id="dob" name="dob" type="text" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" placeholder="Date Of Birth (YYYY-MM-DD) Eg. 1998-12-25" class="form-control input-md" value="<?php echo $thisdob?>">
                </div>				
              </div>
            </div>
            <br>
            <div class="form-group">
              <label class="col-md-5 control-label" for="Gender">Gender</label>
              <div class="col-md-12"> 
                <?php  if ($thisgender==null) : ?>
                  <select class="form-control" id="sel1" name="Gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                  </select>
                <?php  endif ?>
                <?php  if ($thisgender!=null) : ?>
                  <div class="input-group">
                    <?php  if ($thisgender=="Male") : ?>
                      <div class="input-group-addon">
                        <i class="fa fa-male"></i>					
                      </div>
                    <?php  endif ?>
                    <?php  if ($thisgender=="Female") : ?>
                      <div class="input-group-addon">
                        <i class="fa fa-female"></i>					
                      </div>
                    <?php  endif ?>
                    <?php  if ($thisgender=="Other") : ?>
                      <div class="input-group-addon">
                        <i class="fa fa-genderless"></i>					
                      </div>
                    <?php  endif ?>
                    <input id="gender" name="Gender_edit" type="text" class="form-control input-md" value="<?php echo $thisgender?>" disabled>
                  </div>
                <?php  endif ?>
              </div>
            </div>
            <br>

          <!-- Text input-->
            <div class="form-group">
              <label class="col-md-5 control-label" for="Occupation">Occupation</label>  
              <div class="col-md-12">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-briefcase"></i>					
                  </div>
                  <input id="Occupation" name="Occupation" type="text" placeholder="Occupation" class="form-control input-md" value="<?php echo $thisoccupation?>">
                </div>
              </div>
            </div>
            <br>
            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-5 control-label" for="phone_number">Phone number </label>  
              <div class="col-md-12">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>				
                  </div>
                  <input id="phone_number" name="phone_number" type="text" placeholder="Phone number (without '-') Eg 0127786767" class="form-control input-md" value="<?php echo $thisphone?>">
                </div>

                <!-- <div class="input-group othertop">
                  <div class="input-group-addon">
                    <i class="fa fa-mobile fa-1x" style="font-size: 20px;"></i>	
                  </div>
                  <input id="phone_number " name="Secondary Phone Number " type="text" placeholder=" Secondary Phone number " class="form-control input-md">
                </div>		 -->
              </div>
            </div>
            <br>
          <!-- Text input-->
            <div class="form-group">
              <label class="col-md-5 control-label" for="Email Address">Email Address</label>  
                <div class="col-md-12">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-envelope-o"></i>									
                    </div>
                    <input id="Email Address" name="Email Address" type="text" placeholder="Email Address" class="form-control input-md" value="<?php echo $thisemail?>" disabled>							
                  </div>				
                </div>
            </div>
            <br>
            <!-- Textarea -->
            <div class="form-group">
              <label class="col-md-5 control-label" for="description">Describe yourself (max 200 words)</label>
              <div class="col-md-12">                     
                <textarea class="form-control" rows="10"  id="description" name="description" 
                placeholder="I am an enthusiastic person who is looking forward to provide the best event experience." ><?php echo $thisdescription?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-5 control-label" ></label>  
              <div class="col-md-12">
                <input type="submit" name="update" class="btn btn-success" value="Update">
              </div>
            </div>			
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
  <!-- footer -->
  <?php require('partials/footer.php'); ?>
</body>
</html>