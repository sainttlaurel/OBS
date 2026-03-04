<?php
/**
 * Database Setup Script
 * This script will create the database and tables automatically
 */

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlinebanking";

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            margin: 10px 0;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .center {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🏦 Database Setup</h1>";

// Step 1: Connect to MySQL (without database)
$conn = @mysqli_connect($servername, $username, $password);

if (!$conn) {
    echo "<div class='error'>";
    echo "<strong>❌ Connection Failed!</strong><br>";
    echo "Error: " . mysqli_connect_error() . "<br><br>";
    echo "<strong>Solution:</strong><br>";
    echo "1. Make sure MySQL is running in XAMPP Control Panel<br>";
    echo "2. Check if MySQL is using default port 3306<br>";
    echo "3. Verify username is 'root' with no password";
    echo "</div>";
    echo "</div></body></html>";
    exit;
}

echo "<div class='success'>✅ Connected to MySQL server successfully!</div>";

// Step 2: Create database
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if (mysqli_query($conn, $sql)) {
    echo "<div class='success'>✅ Database '$dbname' created successfully!</div>";
} else {
    echo "<div class='error'>❌ Error creating database: " . mysqli_error($conn) . "</div>";
}

// Step 3: Select database
mysqli_select_db($conn, $dbname);

// Step 4: Create users table
$sql = "CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 10000.00,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $sql)) {
    echo "<div class='success'>✅ Users table created successfully!</div>";
} else {
    echo "<div class='error'>❌ Error creating users table: " . mysqli_error($conn) . "</div>";
}

// Step 5: Create transaction table
$sql = "CREATE TABLE IF NOT EXISTS `transaction` (
  `sno` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(100) NOT NULL,
  `receiver` varchar(100) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $sql)) {
    echo "<div class='success'>✅ Transaction table created successfully!</div>";
} else {
    echo "<div class='error'>❌ Error creating transaction table: " . mysqli_error($conn) . "</div>";
}

// Step 6: Check if sample data exists
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    echo "<div class='info'>📝 Inserting sample data...</div>";
    
    // Insert sample users
    $users = [
        ['1001', 'John Doe', 'john@example.com', '1234567890', 'password123', 50000.00],
        ['1002', 'Jane Smith', 'jane@example.com', '0987654321', 'password123', 75000.00],
        ['1003', 'Mike Johnson', 'mike@example.com', '5551234567', 'password123', 30000.00],
        ['1004', 'Sarah Williams', 'sarah@example.com', '5559876543', 'password123', 45000.00],
        ['1005', 'David Brown', 'david@example.com', '5555555555', 'password123', 60000.00]
    ];
    
    $stmt = mysqli_prepare($conn, "INSERT INTO users (user_id, user_name, user_email, phone, password, balance) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($users as $user) {
        mysqli_stmt_bind_param($stmt, "sssssd", $user[0], $user[1], $user[2], $user[3], $user[4], $user[5]);
        mysqli_stmt_execute($stmt);
    }
    
    echo "<div class='success'>✅ Sample users inserted successfully!</div>";
    
    // Insert sample transactions
    $sql = "INSERT INTO transaction (sender, receiver, balance) VALUES 
            ('John Doe', 'Jane Smith', 5000.00),
            ('Jane Smith', 'Mike Johnson', 2500.00)";
    
    if (mysqli_query($conn, $sql)) {
        echo "<div class='success'>✅ Sample transactions inserted successfully!</div>";
    }
} else {
    echo "<div class='info'>ℹ️ Sample data already exists (found {$row['count']} users)</div>";
}

// Step 7: Verify setup
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
$row = mysqli_fetch_assoc($result);
$user_count = $row['count'];

$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM transaction");
$row = mysqli_fetch_assoc($result);
$transaction_count = $row['count'];

echo "<div class='success'>";
echo "<strong>🎉 Setup Complete!</strong><br><br>";
echo "Database: <strong>$dbname</strong><br>";
echo "Users: <strong>$user_count</strong><br>";
echo "Transactions: <strong>$transaction_count</strong><br>";
echo "</div>";

echo "<div class='info'>";
echo "<strong>📋 Test Login Credentials:</strong><br>";
echo "Username: <strong>John Doe</strong><br>";
echo "Password: <strong>password123</strong>";
echo "</div>";

echo "<div class='center'>";
echo "<a href='index.php' class='btn'>🏠 Go to Home Page</a>";
echo "<a href='PHP_Login.php' class='btn'>🔐 Go to Login</a>";
echo "<a href='test_connection.php' class='btn'>🔍 Test Connection</a>";
echo "</div>";

mysqli_close($conn);

echo "</div></body></html>";
?>
