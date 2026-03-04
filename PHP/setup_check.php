<?php
/**
 * Online Banking System - Setup Verification Script
 * This script checks if all requirements are met for the system to work
 */

$errors = [];
$warnings = [];
$success = [];

// Check PHP version
if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
    $success[] = "✅ PHP version " . PHP_VERSION . " is compatible";
} else {
    $errors[] = "❌ PHP version " . PHP_VERSION . " is too old. Requires PHP 7.0 or higher";
}

// Check mysqli extension
if (extension_loaded('mysqli')) {
    $success[] = "✅ MySQLi extension is loaded";
} else {
    $errors[] = "❌ MySQLi extension is not loaded";
}

// Check database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlinebanking";

$conn = @mysqli_connect($servername, $username, $password);

if ($conn) {
    $success[] = "✅ MySQL connection successful";
    
    // Check if database exists
    $db_selected = @mysqli_select_db($conn, $dbname);
    if ($db_selected) {
        $success[] = "✅ Database '$dbname' exists";
        
        // Check users table
        $result = @mysqli_query($conn, "SHOW TABLES LIKE 'users'");
        if ($result && mysqli_num_rows($result) > 0) {
            $success[] = "✅ Users table exists";
            
            // Check users table structure
            $columns = @mysqli_query($conn, "SHOW COLUMNS FROM users");
            $required_columns = ['id', 'user_id', 'user_name', 'user_email', 'phone', 'password', 'balance'];
            $existing_columns = [];
            
            while ($col = mysqli_fetch_assoc($columns)) {
                $existing_columns[] = $col['Field'];
            }
            
            $missing_columns = array_diff($required_columns, $existing_columns);
            if (empty($missing_columns)) {
                $success[] = "✅ Users table structure is correct";
            } else {
                $errors[] = "❌ Users table is missing columns: " . implode(', ', $missing_columns);
            }
            
            // Check if there are users
            $count = @mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
            $row = mysqli_fetch_assoc($count);
            if ($row['total'] > 0) {
                $success[] = "✅ Found " . $row['total'] . " user(s) in database";
            } else {
                $warnings[] = "⚠️ No users found in database. You may need to import sample data.";
            }
            
        } else {
            $errors[] = "❌ Users table does not exist. Please import the SQL file.";
        }
        
        // Check transaction table
        $result = @mysqli_query($conn, "SHOW TABLES LIKE 'transaction'");
        if ($result && mysqli_num_rows($result) > 0) {
            $success[] = "✅ Transaction table exists";
        } else {
            $errors[] = "❌ Transaction table does not exist. Please import the SQL file.";
        }
        
    } else {
        $errors[] = "❌ Database '$dbname' does not exist. Please import the SQL file.";
    }
    
    mysqli_close($conn);
} else {
    $errors[] = "❌ Cannot connect to MySQL. Make sure MySQL is running.";
}

// Check required files
$required_files = [
    'config.php',
    'PHP_Connection.php',
    'PHP_Functions.php',
    'PHP_Login.php',
    'PHP_Signup.php',
    'PHP_Dashboard.php',
    'index.php',
    'navbar.php',
    'transfermoney.php',
    'selecteduserdetail.php',
    'transactionhistory.php'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        $success[] = "✅ File exists: $file";
    } else {
        $errors[] = "❌ Missing file: $file";
    }
}

// Check CSS directory
if (is_dir('CSS') || is_dir('css')) {
    $success[] = "✅ CSS directory exists";
} else {
    $warnings[] = "⚠️ CSS directory not found. Styling may not work properly.";
}

// Check Image directory
if (is_dir('Image') || is_dir('img')) {
    $success[] = "✅ Image directory exists";
} else {
    $warnings[] = "⚠️ Image directory not found. Images may not display.";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Setup Verification - Online Banking System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        .message {
            padding: 12px 15px;
            margin: 8px 0;
            border-radius: 5px;
            font-size: 14px;
            line-height: 1.6;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            font-size: 1.2rem;
        }
        .status-ok {
            background-color: #28a745;
            color: white;
        }
        .status-error {
            background-color: #dc3545;
            color: white;
        }
        .status-warning {
            background-color: #ffc107;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 10px 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .actions {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-top: 30px;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .summary-label {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏦 Setup Verification</h1>
            <p>Online Banking System - Installation Check</p>
        </div>
        
        <div class="content">
            <?php
            $total_checks = count($success) + count($errors) + count($warnings);
            $status = empty($errors) ? (empty($warnings) ? 'ok' : 'warning') : 'error';
            ?>
            
            <div class="summary">
                <div class="summary-item">
                    <div class="summary-number" style="color: #28a745;"><?php echo count($success); ?></div>
                    <div class="summary-label">Passed</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number" style="color: #dc3545;"><?php echo count($errors); ?></div>
                    <div class="summary-label">Errors</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number" style="color: #ffc107;"><?php echo count($warnings); ?></div>
                    <div class="summary-label">Warnings</div>
                </div>
            </div>

            <?php if ($status === 'ok'): ?>
                <div style="text-align: center;">
                    <span class="status-badge status-ok">✅ System Ready!</span>
                    <p style="margin-top: 15px; color: #28a745; font-size: 1.1rem;">
                        All checks passed! Your online banking system is ready to use.
                    </p>
                </div>
            <?php elseif ($status === 'warning'): ?>
                <div style="text-align: center;">
                    <span class="status-badge status-warning">⚠️ System Functional with Warnings</span>
                    <p style="margin-top: 15px; color: #856404; font-size: 1.1rem;">
                        System can work but some features may not function properly.
                    </p>
                </div>
            <?php else: ?>
                <div style="text-align: center;">
                    <span class="status-badge status-error">❌ Setup Incomplete</span>
                    <p style="margin-top: 15px; color: #dc3545; font-size: 1.1rem;">
                        Please fix the errors below before using the system.
                    </p>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
            <div class="section">
                <h2>❌ Errors (Must Fix)</h2>
                <?php foreach ($errors as $error): ?>
                    <div class="message error"><?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($warnings)): ?>
            <div class="section">
                <h2>⚠️ Warnings (Optional)</h2>
                <?php foreach ($warnings as $warning): ?>
                    <div class="message warning"><?php echo $warning; ?></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
            <div class="section">
                <h2>✅ Successful Checks</h2>
                <?php foreach ($success as $succ): ?>
                    <div class="message success"><?php echo $succ; ?></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="actions">
                <h3 style="margin-bottom: 20px;">Next Steps</h3>
                <?php if (empty($errors)): ?>
                    <a href="test_connection.php" class="btn">🔍 Test Database Connection</a>
                    <a href="index.php" class="btn">🏠 Go to Home Page</a>
                    <a href="PHP_Login.php" class="btn">🔐 Login</a>
                    <a href="PHP_Signup.php" class="btn">📝 Register</a>
                <?php else: ?>
                    <p style="color: #dc3545; margin-bottom: 15px;">
                        Please fix the errors above first. Check INSTALLATION_GUIDE.txt for help.
                    </p>
                    <a href="setup_check.php" class="btn">🔄 Refresh Check</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
