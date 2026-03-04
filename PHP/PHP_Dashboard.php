<?php 
session_start();

	include("PHP_Connection.php");
	include("PHP_Functions.php");

	$user_data = check_login($con);
	
	// Redirect to modern dashboard
	header("Location: PHP_Dashboard_Modern.php");
	exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="CSS_Dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="SCRIPT_Dashboard.js"></script>
    <style type="text/css">
      button{
        transition: 1.5s;
      }
      button:hover{
        background-color:#616C6F;
        color: white;
      }
    </style>

</head>
<body>
<header>

    <div class="user">
        <img src="Image\PFP.jpg" alt="profile picture">
        <h3 class="name"><?php echo $user_data['user_name']; ?></h3>
        <p class="post">ACCOUNT USER</p>
    </div>

    <nav class="navbar">
        <ul>
            <li><a href="#Account_Details">Account Details</a></li>
            <li><a href="transfermoney.php">Transfer Funds</a></li>
            <li><a href="transactionhistory.php">Recent Transactions</a></li>
            <li><a href="PHP_Login.php">Log-Out</a></li>
            <li><p class="end">2022 © OBI All rights reserved.</p></li>
        </ul>
    </nav>

</header>
<div id="menu" class="fas fa-bars"></div>
<section class="home" id="home">

    <h3>HI THERE !</h3>
    <h1> <?php echo $user_data['user_name']; ?></h1>
    <p> This is your online banking user-interface
    </p>
  </section>

<section class="about" id="Account_Details">
<h1 class="heading"> Account <span> Details</span></h1>
<div class="row">
    <div class="info">
        <h3> <span> Name : </span> <?php echo $user_data['user_name']; ?></h3>
        <h3> <span> ID_Number: </span> <?php echo $user_data['user_id']; ?></h3>
        <h3> <span> Phone Number: </span> <?php echo $user_data['phone']; ?></h3>
        <h3> <span> Email: </span> <?php echo $user_data['user_email']; ?></h3>
        <h3> <span> Account Balance:</span>₱<?php echo $user_data['balance']; ?></h3>
  </div>

</div>

</section>

</body>
</html>
