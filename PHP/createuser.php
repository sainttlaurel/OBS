<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contacts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/createuser.css">
</head>

<body style="background-color : #ececec;">
<?php
    include 'config.php';
    if(isset($_POST['submit'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $balance = mysqli_real_escape_string($conn, $_POST['balance']);
        
        // Check if email already exists
        $check_query = "SELECT * FROM users WHERE user_email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $check_result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($check_result) > 0){
            echo "<script>alert('Email already exists! Please use a different email.');</script>";
        } else {
            // Generate unique user_id
            $user_id = rand(1000, 9999);
            
            // Insert new user with prepared statement
            $sql = "INSERT INTO users (user_id, user_name, user_email, phone, password, balance) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssssd", $user_id, $name, $email, $phone, $password, $balance);
            $result = mysqli_stmt_execute($stmt);
            
            if($result){
                echo "<script>alert('User has been created!');
                      window.location='transfermoney.php';
                      </script>";
            } else {
                echo "<script>alert('Error creating user: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
?>

<?php
  include 'navbar.php';
?>

        <h2 class="text-center pt-4" style="color : #6c757d;">Add a Contacts</h2>
        <br>

  <div class="background">
  <div class="container">
    <div class="screen">
      <div class="screen-header">
        <div class="screen-header-right">
          <div class="screen-header-ellipsis"></div>
          <div class="screen-header-ellipsis"></div>
          <div class="screen-header-ellipsis"></div>
        </div>
      </div>
      <div class="screen-body">
        <div class="screen-body-item left">
          <img class="img-fluid" src="img/user3.jpg" style="border: none; border-radius: 10%;">
        </div>
        <div class="screen-body-item">
          <form class="app-form" method="post">
            <div class="app-form-group">
              <input class="app-form-control" placeholder="FULLNAME" type="text" name="name" required>
            </div>
            <div class="app-form-group">
              <input class="app-form-control" placeholder="EMAIL" type="email" name="email" required>
            </div>
            <div class="app-form-group">
              <input class="app-form-control" placeholder="PHONE NUMBER" type="text" name="phone" required>
            </div>
            <div class="app-form-group">
              <input class="app-form-control" placeholder="PASSWORD" type="password" name="password" required>
            </div>
            <div class="app-form-group">
              <input class="app-form-control" placeholder="INITIAL BALANCE" type="number" name="balance" value="10000" required>
            </div>
            <br>
            <div class="app-form-group button">
              <input class="app-form-button" style="color:#4d865a;" type="submit" value="CREATE USER" name="submit"></input>
              <input class="app-form-button" style="color:#dc3545;" type="reset" value="RESET" name="reset"></input>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<footer class="text-center mt-5 py-2">
<p>2022 © OBI All rights reserved. <b>Online Banking System</b></p>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>