<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>🏦 Online Banking System - Connection Test</h1>
    
    <?php
    // Test database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "onlinebanking";

    echo "<div class='info'><strong>Testing connection to database...</strong></div>";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if(!$conn){
        echo "<div class='error'>";
        echo "<strong>❌ Connection Failed!</strong><br>";
        echo "Error: " . mysqli_connect_error() . "<br><br>";
        echo "<strong>Possible Solutions:</strong><br>";
        echo "1. Make sure MySQL is running in XAMPP<br>";
        echo "2. Import the database file: DATABASE FILE/onlinebanking.sql<br>";
        echo "3. Check database credentials in config.php<br>";
        echo "</div>";
    } else {
        echo "<div class='success'>";
        echo "<strong>✅ Database Connection Successful!</strong><br>";
        echo "Connected to database: <strong>$dbname</strong>";
        echo "</div>";

        // Check if users table exists
        $result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
        if(mysqli_num_rows($result) > 0) {
            echo "<div class='success'><strong>✅ Users table exists</strong></div>";
            
            // Count users
            $count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
            $count_row = mysqli_fetch_assoc($count_result);
            echo "<div class='info'><strong>Total Users:</strong> " . $count_row['total'] . "</div>";
            
            // Display sample users
            echo "<h2>Sample User Accounts</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Phone</th><th>Balance</th></tr>";
            
            $users_result = mysqli_query($conn, "SELECT user_id, user_name, user_email, phone, balance FROM users LIMIT 5");
            while($row = mysqli_fetch_assoc($users_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['user_email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>₱" . number_format($row['balance'], 2) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<div class='info'>";
            echo "<strong>Test Login Credentials:</strong><br>";
            echo "Username: <strong>John Doe</strong><br>";
            echo "Password: <strong>password123</strong>";
            echo "</div>";
            
        } else {
            echo "<div class='error'>";
            echo "<strong>❌ Users table not found!</strong><br>";
            echo "Please import the database file: DATABASE FILE/onlinebanking.sql";
            echo "</div>";
        }

        // Check if transaction table exists
        $result = mysqli_query($conn, "SHOW TABLES LIKE 'transaction'");
        if(mysqli_num_rows($result) > 0) {
            echo "<div class='success'><strong>✅ Transaction table exists</strong></div>";
            
            // Count transactions
            $count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaction");
            $count_row = mysqli_fetch_assoc($count_result);
            echo "<div class='info'><strong>Total Transactions:</strong> " . $count_row['total'] . "</div>";
        } else {
            echo "<div class='error'><strong>❌ Transaction table not found!</strong></div>";
        }

        mysqli_close($conn);
    }
    ?>

    <div style="margin-top: 30px;">
        <a href="index.php" class="btn">🏠 Go to Home Page</a>
        <a href="PHP_Login.php" class="btn">🔐 Go to Login</a>
        <a href="PHP_Signup.php" class="btn">📝 Register New Account</a>
    </div>

    <div style="margin-top: 30px; padding: 20px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 5px;">
        <strong>⚠️ Security Warning:</strong><br>
        This test file shows sensitive information. Delete this file (test_connection.php) before deploying to production.
    </div>

</body>
</html>
