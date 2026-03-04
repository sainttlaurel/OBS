<?php
// Notification Helper Functions

function add_notification($con, $user_name, $title, $message, $type = 'info') {
    // Check if table exists first
    $check = mysqli_query($con, "SHOW TABLES LIKE 'notifications'");
    if(mysqli_num_rows($check) == 0) {
        return false; // Table doesn't exist yet
    }
    
    $query = "INSERT INTO notifications (user_name, title, message, type) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $user_name, $title, $message, $type);
    return mysqli_stmt_execute($stmt);
}

function get_unread_count($con, $user_name) {
    // Check if table exists first
    $check = mysqli_query($con, "SHOW TABLES LIKE 'notifications'");
    if(mysqli_num_rows($check) == 0) {
        return 0; // Table doesn't exist yet, return 0
    }
    
    $query = "SELECT COUNT(*) as count FROM notifications WHERE user_name = ? AND is_read = 0";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $user_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function get_recent_notifications($con, $user_name, $limit = 5) {
    // Check if table exists first
    $check = mysqli_query($con, "SHOW TABLES LIKE 'notifications'");
    if(mysqli_num_rows($check) == 0) {
        return false; // Table doesn't exist yet
    }
    
    $query = "SELECT * FROM notifications WHERE user_name = ? ORDER BY created_at DESC LIMIT ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "si", $user_name, $limit);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
?>
