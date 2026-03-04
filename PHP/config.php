<?php
	// Database configuration
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "onlinebanking";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	// Check connection
	if(!$conn){
		die("Could not connect to the database due to the following error --> ".mysqli_connect_error());
	}

	// Set charset to utf8mb4
	mysqli_set_charset($conn, "utf8mb4");
?>