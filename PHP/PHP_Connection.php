<?php
// Database configuration
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "onlinebanking";

// Create connection
$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

if(!$con)
{
	die("Failed to connect to database: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($con, "utf8mb4");
?>