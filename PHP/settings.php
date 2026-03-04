<?php 
session_start();
include("PHP_Connection.php");
include("PHP_Functions.php");

$user_data = check_login($con);
$success_message = '';
$error_message = '';

// Handle name change
if(isset($_POST['update_name'])) {
    $new_name = mysqli_real_escape_string($con, $_POST['new_name']);
    
    if(!empty($new_name) && !is_numeric($new_name)) {
        $query = "UPDATE users SET user_name = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $new_name, $user_data['user_id']);
        
        if(mysqli_stmt_execute($stmt)) {
            // Update transactions with old name
            $old_name = $user_data['user_name'];
            
            $update_sender = "UPDATE transaction SET sender = ? WHERE sender = ?";
            $stmt = mysqli_prepare($con, $update_sender);
            mysqli_stmt_bind_param($stmt, "ss", $new_name, $old_name);
            mysqli_stmt_execute($stmt);
            
            $update_receiver = "UPDATE transaction SET receiver = ? WHERE receiver = ?";
            $stmt = mysqli_prepare($con, $update_receiver);
            mysqli_stmt_bind_param($stmt, "ss", $new_name, $old_name);
            mysqli_stmt_execute($stmt);
            
            // Update notifications
            $update_notif = "UPDATE notifications SET user_name = ? WHERE user_name = ?";
            $stmt = mysqli_prepare($con, $update_notif);
            mysqli_stmt_bind_param($stmt, "ss", $new_name, $old_name);
            mysqli_stmt_execute($stmt);
            
            $success_message = "Name updated successfully!";
            $user_data['user_name'] = $new_name;
        } else {
            $error_message = "Failed to update name.";
        }
    } else {
        $error_message = "Please enter a valid name.";
    }
}

// Handle email change
if(isset($_POST['update_email'])) {
    $new_email = mysqli_real_escape_string($con, $_POST['new_email']);
    
    if(!empty($new_email) && filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        // Check if email exists
        $check = "SELECT * FROM users WHERE user_email = ? AND user_id != ?";
        $stmt = mysqli_prepare($con, $check);
        mysqli_stmt_bind_param($stmt, "ss", $new_email, $user_data['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result) > 0) {
            $error_message = "Email already in use.";
        } else {
            $query = "UPDATE users SET user_email = ? WHERE user_id = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "ss", $new_email, $user_data['user_id']);
            
            if(mysqli_stmt_execute($stmt)) {
                $success_message = "Email updated successfully!";
                $user_data['user_email'] = $new_email;
            } else {
                $error_message = "Failed to update email.";
            }
        }
    } else {
        $error_message = "Please enter a valid email.";
    }
}

// Handle phone change
if(isset($_POST['update_phone'])) {
    $new_phone = mysqli_real_escape_string($con, $_POST['new_phone']);
    
    if(!empty($new_phone)) {
        $query = "UPDATE users SET phone = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $new_phone, $user_data['user_id']);
        
        if(mysqli_stmt_execute($stmt)) {
            $success_message = "Phone number updated successfully!";
            $user_data['phone'] = $new_phone;
        } else {
            $error_message = "Failed to update phone number.";
        }
    } else {
        $error_message = "Please enter a valid phone number.";
    }
}

// Handle password change
if(isset($_POST['update_password'])) {
    $current_password = mysqli_real_escape_string($con, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);
    
    if($current_password === $user_data['password']) {
        if($new_password === $confirm_password) {
            if(strlen($new_password) >= 6) {
                $query = "UPDATE users SET password = ? WHERE user_id = ?";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, "ss", $new_password, $user_data['user_id']);
                
                if(mysqli_stmt_execute($stmt)) {
                    $success_message = "Password updated successfully!";
                } else {
                    $error_message = "Failed to update password.";
                }
            } else {
                $error_message = "Password must be at least 6 characters.";
            }
        } else {
            $error_message = "New passwords do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}

// Refresh user data
$query = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $user_data['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - OBI Banking</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/modern-style.css">
    
    <style>
        body {
            background: #f5f7fa;
        }
        
        .settings-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .settings-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .settings-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .export-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .export-btn {
            padding: 1rem;
            border-radius: 12px;
            border: 2px solid var(--border-color);
            background: white;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .export-btn:hover {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.05);
            transform: translateY(-2px);
        }
        
        .export-btn i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
            color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="loading-screen">
        <div class="loader"></div>
        <div class="loading-text">Loading Settings...</div>
    </div>

    <div class="settings-container">
        <div class="settings-header">
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-cog"></i> Account Settings
            </h1>
            <p style="color: var(--text-secondary);">Manage your account information and preferences</p>
            <div style="margin-top: 1rem;">
                <a href="PHP_Dashboard_Modern.php" class="btn-modern btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <?php if($success_message): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
        </div>
        <?php endif; ?>

        <?php if($error_message): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
        </div>
        <?php endif; ?>

        <!-- Personal Information -->
        <div class="settings-section">
            <h2 class="section-title">
                <div class="section-icon"><i class="fas fa-user"></i></div>
                Personal Information
            </h2>
            
            <form method="post">
                <div class="input-group">
                    <label class="input-label">Full Name</label>
                    <input type="text" name="new_name" class="modern-input" 
                           value="<?php echo htmlspecialchars($user_data['user_name']); ?>" required>
                </div>
                <button type="submit" name="update_name" class="btn-modern btn-primary">
                    <i class="fas fa-save"></i> Update Name
                </button>
            </form>
        </div>

        <!-- Contact Information -->
        <div class="settings-section">
            <h2 class="section-title">
                <div class="section-icon"><i class="fas fa-envelope"></i></div>
                Contact Information
            </h2>
            
            <form method="post">
                <div class="form-row">
                    <div class="input-group">
                        <label class="input-label">Email Address</label>
                        <input type="email" name="new_email" class="modern-input" 
                               value="<?php echo htmlspecialchars($user_data['user_email']); ?>" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label">Phone Number</label>
                        <input type="text" name="new_phone" class="modern-input" 
                               value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" name="update_email" class="btn-modern btn-primary">
                        <i class="fas fa-save"></i> Update Email
                    </button>
                    <button type="submit" name="update_phone" class="btn-modern btn-primary">
                        <i class="fas fa-save"></i> Update Phone
                    </button>
                </div>
            </form>
        </div>

        <!-- Security -->
        <div class="settings-section">
            <h2 class="section-title">
                <div class="section-icon"><i class="fas fa-lock"></i></div>
                Security
            </h2>
            
            <form method="post">
                <div class="input-group">
                    <label class="input-label">Current Password</label>
                    <input type="password" name="current_password" class="modern-input" 
                           placeholder="Enter current password" required>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <label class="input-label">New Password</label>
                        <input type="password" name="new_password" class="modern-input" 
                               placeholder="Enter new password" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="modern-input" 
                               placeholder="Confirm new password" required>
                    </div>
                </div>
                <button type="submit" name="update_password" class="btn-modern btn-primary">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
        </div>

        <!-- Export Data -->
        <div class="settings-section">
            <h2 class="section-title">
                <div class="section-icon"><i class="fas fa-download"></i></div>
                Export Transaction Data
            </h2>
            
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                Download your complete transaction history in various formats
            </p>
            
            <div class="export-options">
                <a href="export_transactions.php?format=csv" class="export-btn">
                    <i class="fas fa-file-csv"></i>
                    Export as CSV
                </a>
                <a href="export_transactions.php?format=pdf" class="export-btn">
                    <i class="fas fa-file-pdf"></i>
                    Export as PDF
                </a>
                <a href="export_transactions.php?format=excel" class="export-btn">
                    <i class="fas fa-file-excel"></i>
                    Export as Excel
                </a>
            </div>
        </div>

        <!-- Account Info -->
        <div class="settings-section">
            <h2 class="section-title">
                <div class="section-icon"><i class="fas fa-info-circle"></i></div>
                Account Information
            </h2>
            
            <div style="display: grid; gap: 1rem;">
                <div style="padding: 1rem; background: var(--light-color); border-radius: 12px;">
                    <strong>Account ID:</strong> <?php echo htmlspecialchars($user_data['user_id']); ?>
                </div>
                <div style="padding: 1rem; background: var(--light-color); border-radius: 12px;">
                    <strong>Current Balance:</strong> ₱<?php echo number_format($user_data['balance'], 2); ?>
                </div>
                <div style="padding: 1rem; background: var(--light-color); border-radius: 12px;">
                    <strong>Member Since:</strong> <?php echo date('F d, Y', strtotime($user_data['created_at'])); ?>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/loading.js"></script>
</body>
</html>
