<?php
/**
 * Setup Notifications Table
 * Run this once to create the notifications table
 */

include("config.php");

echo "<!DOCTYPE html>
<html>
<head>
    <title>Setup Notifications</title>
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
        h1 {
            color: #333;
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
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔔 Setup Notifications System</h1>";

// Create notifications table
$sql = "CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('success','info','warning') NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_name` (`user_name`),
  KEY `is_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $sql)) {
    echo "<div class='success'>✅ Notifications table created successfully!</div>";
} else {
    echo "<div class='error'>❌ Error creating table: " . mysqli_error($conn) . "</div>";
}

// Check if notifications already exist
$check = mysqli_query($conn, "SELECT COUNT(*) as count FROM notifications");
$row = mysqli_fetch_assoc($check);

if($row['count'] == 0) {
    echo "<div class='success'>📝 Adding welcome notifications for all users...</div>";
    
    // Get all users
    $users_query = mysqli_query($conn, "SELECT user_name FROM users");
    $count = 0;
    
    while($user = mysqli_fetch_assoc($users_query)) {
        $user_name = $user['user_name'];
        
        // Welcome notification
        $sql = "INSERT INTO notifications (user_name, title, message, type) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        $title = "Welcome to OBI Banking!";
        $message = "Your account has been successfully set up. Start exploring our features!";
        $type = "success";
        mysqli_stmt_bind_param($stmt, "ssss", $user_name, $title, $message, $type);
        mysqli_stmt_execute($stmt);
        
        // Feature notification
        $title = "New Features Available";
        $message = "Check out our new Settings page to update your profile and export transactions!";
        $type = "info";
        mysqli_stmt_bind_param($stmt, "ssss", $user_name, $title, $message, $type);
        mysqli_stmt_execute($stmt);
        
        // Security reminder
        $title = "Security Reminder";
        $message = "Remember to change your password regularly for better account security.";
        $type = "warning";
        mysqli_stmt_bind_param($stmt, "ssss", $user_name, $title, $message, $type);
        mysqli_stmt_execute($stmt);
        
        $count++;
    }
    
    echo "<div class='success'>✅ Added notifications for $count users!</div>";
} else {
    echo "<div class='success'>ℹ️ Notifications already exist ({$row['count']} notifications found)</div>";
}

echo "<div class='success'>";
echo "<strong>🎉 Setup Complete!</strong><br><br>";
echo "The notifications system is now ready to use!<br>";
echo "Users will receive notifications for:<br>";
echo "• Money transfers (sent/received)<br>";
echo "• Account updates<br>";
echo "• System announcements<br>";
echo "</div>";

echo "<div style='text-align: center; margin-top: 20px;'>";
echo "<a href='PHP_Dashboard_Modern.php' class='btn'>Go to Dashboard</a>";
echo "<a href='notifications.php' class='btn'>View Notifications</a>";
echo "</div>";

// Auto-redirect after 3 seconds
echo "<script>
setTimeout(function() {
    window.location.href = 'PHP_Dashboard_Modern.php';
}, 3000);
</script>";

mysqli_close($conn);

echo "</div></body></html>";
?>
