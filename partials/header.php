<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #00838f;">
  <div class="container">
    <a class="navbar-brand" href="/EventWeb/home.php">EventWeb</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="/EventWeb/home.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/EventWeb/event.php">Browse Events</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="/signup.php">Sign Up</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/login.php">Login</a>
        </li> -->
        <?php if(isset($_SESSION['username'])): ?>
					<li class="nav-item"><a class="nav-link" href="/EventWeb/createEvent.php">Create Event</a></li>
					<li class="nav-item"><a class="nav-link" href="/EventWeb/dashboard.php">Dashboard</a></li>
					<li class="nav-item"><a class="nav-link" href="/EventWeb/profile.php">My Profile</a><li>
          <li class="nav-item"><a class="nav-link" href="/EventWeb/cart.php">My Cart<span class="badge"></span></a><li>
				<?php endif; ?>
				<?php if(isset($_SESSION['username'])): ?>
					<li class="nav-item"><a class="nav-link" href="/EventWeb/logout.php">Logout</a></li>
				<?php else: ?>
					<li class="nav-item"><a class="nav-link" href="/EventWeb/signup.php">Sign Up</a></li>
					<li class="nav-item"><a class="nav-link" href="/EventWeb/login.php">Login</a></li>
				<?php endif; ?>
        <!-- To add a new file to header -->
        <!-- <li class="nav-item">
          <a class="nav-link" href="filename.php">filename/suitable name</a>
        </li> -->
      </ul>
    </div>
  </div>
</nav>