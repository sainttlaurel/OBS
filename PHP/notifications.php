<?php 
session_start();
include("PHP_Connection.php");
include("PHP_Functions.php");

$user_data = check_login($con);
$user_name = $user_data['user_name'];

// Mark notification as read
if(isset($_POST['mark_read'])) {
    $notif_id = mysqli_real_escape_string($con, $_POST['notif_id']);
    $query = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_name = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "is", $notif_id, $user_name);
    mysqli_stmt_execute($stmt);
    echo json_encode(['success' => true]);
    exit;
}

// Mark all as read
if(isset($_POST['mark_all_read'])) {
    $query = "UPDATE notifications SET is_read = 1 WHERE user_name = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $user_name);
    mysqli_stmt_execute($stmt);
    echo json_encode(['success' => true]);
    exit;
}

// Get notifications
$query = "SELECT * FROM notifications WHERE user_name = ? ORDER BY created_at DESC LIMIT 20";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $user_name);
mysqli_stmt_execute($stmt);
$notifications = mysqli_stmt_get_result($stmt);

// Count unread
$count_query = "SELECT COUNT(*) as unread FROM notifications WHERE user_name = ? AND is_read = 0";
$stmt = mysqli_prepare($con, $count_query);
mysqli_stmt_bind_param($stmt, "s", $user_name);
mysqli_stmt_execute($stmt);
$count_result = mysqli_stmt_get_result($stmt);
$unread_count = mysqli_fetch_assoc($count_result)['unread'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - OBI Banking</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/modern-style.css">
    
    <style>
        body {
            background: #f5f7fa;
        }
        
        .notifications-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .notifications-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .header-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        .unread-badge {
            background: var(--danger-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .notification-item {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            gap: 1rem;
            transition: var(--transition);
            cursor: pointer;
        }
        
        .notification-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
        }
        
        .notification-item.unread {
            border-left: 4px solid var(--primary-color);
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.05) 0%, white 100%);
        }
        
        .notif-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .notif-icon.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        .notif-icon.info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }
        
        .notif-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }
        
        .notif-content {
            flex: 1;
        }
        
        .notif-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .notif-message {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .notif-time {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
        }
        
        .empty-icon {
            font-size: 4rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="loading-screen">
        <div class="loader"></div>
        <div class="loading-text">Loading Notifications...</div>
    </div>

    <div class="notifications-container">
        <div class="notifications-header">
            <div class="header-top">
                <h1 class="header-title">
                    <i class="fas fa-bell"></i> Notifications
                </h1>
                <?php if($unread_count > 0): ?>
                <span class="unread-badge"><?php echo $unread_count; ?> Unread</span>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <a href="PHP_Dashboard_Modern.php" class="btn-modern btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <?php if($unread_count > 0): ?>
                <button onclick="markAllRead()" class="btn-modern btn-primary">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if(mysqli_num_rows($notifications) > 0): ?>
            <?php while($notif = mysqli_fetch_assoc($notifications)): ?>
            <div class="notification-item <?php echo $notif['is_read'] ? '' : 'unread'; ?>" 
                 onclick="markAsRead(<?php echo $notif['id']; ?>)">
                <div class="notif-icon <?php echo $notif['type']; ?>">
                    <i class="fas fa-<?php 
                        echo $notif['type'] == 'success' ? 'check-circle' : 
                            ($notif['type'] == 'warning' ? 'exclamation-triangle' : 'info-circle'); 
                    ?>"></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title"><?php echo htmlspecialchars($notif['title']); ?></div>
                    <div class="notif-message"><?php echo htmlspecialchars($notif['message']); ?></div>
                    <div class="notif-time">
                        <i class="fas fa-clock"></i> 
                        <?php echo date('M d, Y h:i A', strtotime($notif['created_at'])); ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h3>No Notifications</h3>
                <p>You're all caught up! Check back later for updates.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="assets/js/loading.js"></script>
    <script>
        function markAsRead(notifId) {
            fetch('notifications.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'mark_read=1&notif_id=' + notifId
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }
        
        function markAllRead() {
            fetch('notifications.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'mark_all_read=1'
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>
