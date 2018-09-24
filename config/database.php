<?php


    // Connect to MySQL
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
  die("Could not connect: " . mysqli_connect_error());
}

    // Connect to Event Web
$db_selected = mysqli_select_db($conn, "eventweb");

if (!$db_selected) {
        // If we couldn't, then it either doesn't exist, or we can't see it.
  $sql = "CREATE DATABASE eventweb";

  if (mysqli_query($conn, $sql)) {
    echo "Database eventweb created.<br>";

    $db_selected = mysqli_select_db($conn, "eventweb");

    if ($db_selected) {
      echo "Database eventweb selected.<br>";
    }

    //sql to create table cart
    $cart = "CREATE TABLE `cart` (
            `id` int(9) NOT NULL,
            `user_id` int(9) NOT NULL,
            `activation` bit(1) DEFAULT b'0'
            );";

    if (mysqli_query($conn, $cart)) {
      echo "Table cart created successfully.<br>";
    }
            //sql to create table user
    $user = "CREATE TABLE `user` (
            `id` int(9) NOT NULL,
            `name` text NOT NULL,
            `password` text NOT NULL,
            `email` text NOT NULL,
            `full_name` text,
            `dob` text,
            `gender` text,
            `occupation` text,
            `phone` text,
            `description` text
            );";

    if (mysqli_query($conn, $user)) {
      echo "Table user created successfully.<br>";
    }

            //sql to create table EVENT
    $event = "CREATE TABLE `event` (
              `id` int(9) NOT NULL,
              `name` text NOT NULL,
              `description` text NOT NULL,
              `date` date NOT NULL,
              `time` time NOT NULL,
              `venue` text NOT NULL,
              `user_id` int(9) NOT NULL,
              `price` decimal(10,2) NOT NULL
              );";

    if (mysqli_query($conn, $event)) {
      echo "Table event created successfully.<br>";
    }
  
            //sql to create table participation
    $part = "CREATE TABLE `participation` (
            `event_id` int(9) NOT NULL,
            `user_id` int(9) NOT NULL
            );";

    if (mysqli_query($conn, $part)) {
      echo "Table participation created successfully.<br>";
    }

            //sql to insert data into table user
    $user_data = "INSERT INTO `user` (`id`, `name`, `password`, `email`, `full_name`, `dob`, `gender`, `occupation`, `phone`, `description`) VALUES
                  (1, 'john', '$2y$10\$dROib0297cJYOv6fwHW8sucVpVvhT1ygxOUSMJEMchNWyNULJNtWu', 'john@email.com', 'John Cena', '1998-03-29', 'Male', 'Wrestler', '0177778889', 'Welcome to my world. Hey'),
                  (2, 'peter', '$2y$10\$oaMFVPcrtiNGSio2PeIohu8/fIO/O1DYUD3HX2YgXsRI9bzN54vl2', 'peter@email.com', NULL, NULL, NULL, NULL, NULL, NULL),
                  (3, 'jane', '$2y$10\$DccZtUH4U6NG/W0hC4gb0el8j4.hg8cUJjvqEs73Jn8JI/h.Mq8UG', 'jane@email.com', NULL, NULL, NULL, NULL, NULL, NULL),
                  (4, 'admin', '$2y$10\$HyovJMYu1rCIFI57r1FcSusmixZ0z9vfRj.N9/EItfRpZWMJpWoQ.', 'admin@email.com', 'Admin Pro', '1996-02-14', NULL, 'Professional Organiser', '0197796758', 'I am a professional event organiser. Thank you. Lala x2'),
                  (5, 'alfred20697', '$2y$10\$jV8dPUbZrLGo3zwZTF0tPeogEStJaaxDqmW/I7os90hLirc5dHZX.', 'alfred97620@gmail.com', 'Alfred Loo WH', '1997-06-20', 'Male', 'Student in MMU', '0197796758', 'Hello, I am Alfred from MMU. YNWA'),
                  (6, 'Meapy', '$2y$10\$pxLt.3KsdJo5Q123.awZTO/EkOsZ9MwtlD0ymWnswlMEVdG06Jvpu', 'meapy@hotmail.com', 'Meapy Meap', '1998-10-10', 'Male', 'keke', '0123456789', 'kek'),
                  (7, 'Meapy2', '$2y$10$.0snN6MVzZxXxgcWIKnuZO3rL.067UWNnNgYclffqR.oQz7kF3Lmy', 'meapymeap@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL);";

    if (mysqli_query($conn, $user_data)) {
      echo "Data for table user inserted successfully.<br>";
    }

            //sql to create table event
    $event_data = "INSERT INTO `event` (`id`, `name`, `description`, `date`, `time`, `venue`, `user_id`, `price`) VALUES
                  (1, 'Web Development Workshop', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2018-03-10', '20:00:00', 'Classroom', 1, '10.00'),
                  (2, 'Career Fair', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2018-01-05', '09:00:00', 'Grand Hall', 2, '100.00'),
                  (3, 'Japanese Cultural Festival', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', '2018-04-05', '19:00:00', 'National Sports Complex', 3, '1000.00'),
                  (4, 'Charity Run', 'This is a charity run. ', '2018-01-21', '07:30:00', 'D\'Pulze Cyberjaya', 4, '500.00'),
                  (5, 'Fun Fair', 'This is a fun fair.', '2018-01-21', '09:00:00', 'Shaftsbury Square', 4, '50.00'),
                  (6, 'BBQ Night', 'This is a bbq night.', '2018-01-31', '19:00:00', 'FCI, Multimedia University Cyberjaya', 4, '10.10'),
                  (12, 'Kikiland', 'idfkm', '2018-09-30', '11:11:00', 'idfk', 7, '0.50'),
                  (13, 'Mimiland', 'eh', '2020-11-11', '11:11:00', 'Irdmn', 7, '0.01');";

    if (mysqli_query($conn, $event_data)) {
      echo "Data for table event inserted successfully.<br>";
    }

    $cart_data = "INSERT INTO `cart` (`id`, `user_id`, `activation`) VALUES
                  (12, 6, b'0'),
                  (13, 6, b'0');";

    if (mysqli_query($conn, $cart_data)) {
      echo "Data for table cart inserted successfully.<br>";
    }

            //sql to create table images
    $part_data = "INSERT INTO `participation` (`event_id`, `user_id`) VALUES
                  (1, 1),
                  (1, 2),
                  (1, 3),
                  (1, 4),
                  (1, 5),
                  (2, 3),
                  (3, 1),
                  (3, 2),
                  (3, 3),
                  (3, 4),
                  (3, 5),
                  (6, 1),
                  (6, 2),
                  (6, 3),
                  (6, 4),
                  (12, 7),
                  (13, 7);";

    if (mysqli_query($conn, $part_data)) {
      echo "Data for table participation inserted successfully.<br>";
    }
        
            //Indexes for all tables
    if (mysqli_query($conn, "ALTER TABLE `event`
            ADD PRIMARY KEY (`id`),
            ADD KEY `id` (`user_id`);")) {
      echo "Table event indexed successfully.<br>";
    }

    if (mysqli_query($conn, "ALTER TABLE `participation`
            ADD PRIMARY KEY (`event_id`,`user_id`),
            ADD KEY `participation_ibfk_2` (`user_id`);")) {
      echo "Table participation indexed successfully.<br>";
    }

    if (mysqli_query($conn, "ALTER TABLE `user`
            ADD PRIMARY KEY (`id`);")) {
      echo "Table user indexed successfully.<br>";
    }

    if (mysqli_query($conn, "ALTER TABLE `cart`
            ADD PRIMARY KEY (`id`,`user_id`),
            ADD UNIQUE KEY `id` (`id`,`user_id`);")) {
      echo "Table cart indexed successfully.<br>";
    }

            //Add auto increment for table
    if (mysqli_query($conn, "ALTER TABLE `event`
            MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;")) {
      echo "Auto increment for table event updated successfully.<br>";
    }

    if (mysqli_query($conn, "ALTER TABLE `user`
            MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;")) {
      echo "Auto increment for table user updated successfully.<br>";
    }

            //Add foreign key for table
    if (mysqli_query($conn, "ALTER TABLE `event`
            ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);")) {
      echo "Foreign key for table event updated successfully.<br>";
    }

    if (mysqli_query($conn, "ALTER TABLE `participation`
            ADD CONSTRAINT `participation_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
            ADD CONSTRAINT `participation_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);")) {
      echo "Foreign key for table participation updated successfully.<br><br>";
    }
  } else {
    echo "Error creating database: " . mysqli_error($conn) . "\n";
  }
}
echo "Click <a href=\"..\home.php\">here</a> and go to home page.";
?>
