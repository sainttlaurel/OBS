<?php 
session_start();

	include("PHP_Connection.php");
	include("PHP_Functions.php");
	include("notification_helper.php");

	$user_data = check_login($con);
	
	// Get unread notification count
	$unread_count = get_unread_count($con, $user_data['user_name']);
	
	// Get recent transactions
	$user_name = $user_data['user_name'];
	$query = "SELECT * FROM transaction WHERE sender = ? OR receiver = ? ORDER BY datetime DESC LIMIT 5";
	$stmt = mysqli_prepare($con, $query);
	mysqli_stmt_bind_param($stmt, "ss", $user_name, $user_name);
	mysqli_stmt_execute($stmt);
	$recent_transactions = mysqli_stmt_get_result($stmt);
	
	// Calculate statistics
	$sent_query = "SELECT SUM(balance) as total FROM transaction WHERE sender = ?";
	$stmt = mysqli_prepare($con, $sent_query);
	mysqli_stmt_bind_param($stmt, "s", $user_name);
	mysqli_stmt_execute($stmt);
	$sent_result = mysqli_stmt_get_result($stmt);
	$sent_data = mysqli_fetch_assoc($sent_result);
	$total_sent = $sent_data['total'] ?? 0;
	
	$received_query = "SELECT SUM(balance) as total FROM transaction WHERE receiver = ?";
	$stmt = mysqli_prepare($con, $received_query);
	mysqli_stmt_bind_param($stmt, "s", $user_name);
	mysqli_stmt_execute($stmt);
	$received_result = mysqli_stmt_get_result($stmt);
	$received_data = mysqli_fetch_assoc($received_result);
	$total_received = $received_data['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OBI Banking</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Modern Styles -->
    <link rel="stylesheet" href="assets/css/modern-style.css">
    
    <style>
        body {
            background: #f5f7fa;
        }
        
        .dashboard-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        
        .sidebar {
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            padding: 2rem 0;
        }
        
        .sidebar-brand {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }
        
        .sidebar-brand h2 {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-item {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
        }
        
        .sidebar-link:hover,
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
        }
        
        .main-content {
            padding: 2rem;
        }
        
        .top-bar {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
        }
        
        .welcome-text h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .welcome-text p {
            color: var(--text-secondary);
        }
        
        .user-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--light-color);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        
        .notification-btn:hover {
            background: var(--border-color);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 18px;
            height: 18px;
            background: var(--danger-color);
            border-radius: 50%;
            font-size: 0.7rem;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-icon.balance {
            background: linear-gradient(135deg, #008B8B, #2F4F4F);
            color: white;
        }
        
        .stat-icon.sent {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
        
        .stat-icon.received {
            background: rgba(0, 200, 83, 0.1);
            color: var(--success-color);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }
        
        .card-modern {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        .view-all-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .transaction-list {
            list-style: none;
        }
        
        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .transaction-item:last-child {
            border-bottom: none;
        }
        
        .transaction-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .transaction-details h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .transaction-details p {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
        
        .transaction-amount {
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .transaction-amount.positive {
            color: var(--success-color);
        }
        
        .transaction-amount.negative {
            color: var(--danger-color);
        }
        
        .quick-actions {
            display: grid;
            gap: 1rem;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            text-decoration: none;
            transition: var(--transition);
            font-weight: 600;
        }
        
        .action-btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .action-btn-primary:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
        }
        
        .action-btn-outline {
            background: white;
            border: 2px solid var(--border-color);
            color: var(--text-primary);
        }
        
        .action-btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateX(5px);
        }
        
        @media (max-width: 1024px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loader"></div>
        <div class="loading-text">Loading Dashboard...</div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h2><i class="fas fa-university"></i> OBI</h2>
            </div>
            
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="PHP_Dashboard_Modern.php" class="sidebar-link active">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="transfermoney.php" class="sidebar-link">
                        <i class="fas fa-exchange-alt"></i> Transfer
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="transactionhistory.php" class="sidebar-link">
                        <i class="fas fa-history"></i> History
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="createuser.php" class="sidebar-link">
                        <i class="fas fa-user-plus"></i> Add User
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="removeuser.php" class="sidebar-link">
                        <i class="fas fa-user-minus"></i> Remove User
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="notifications.php" class="sidebar-link">
                        <i class="fas fa-bell"></i> Notifications
                        <?php if($unread_count > 0): ?>
                        <span style="margin-left: auto; background: var(--danger-color); color: white; padding: 0.25rem 0.5rem; border-radius: 10px; font-size: 0.75rem;">
                            <?php echo $unread_count; ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="settings.php" class="sidebar-link">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="PHP_Login.php" class="sidebar-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="welcome-text">
                    <h1>Welcome back, <?php echo htmlspecialchars($user_data['user_name']); ?>! 👋</h1>
                    <p>Here's what's happening with your account today</p>
                </div>
                <div class="user-actions">
                    <a href="notifications.php" class="notification-btn" style="text-decoration: none; color: inherit;">
                        <i class="fas fa-bell"></i>
                        <?php if($unread_count > 0): ?>
                        <span class="notification-badge"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="settings.php" class="notification-btn" style="text-decoration: none; color: inherit;">
                        <i class="fas fa-cog"></i>
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">₱<?php echo number_format($user_data['balance'], 2); ?></div>
                            <div class="stat-label">Current Balance</div>
                        </div>
                        <div class="stat-icon balance">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">₱<?php echo number_format($total_sent, 2); ?></div>
                            <div class="stat-label">Total Sent</div>
                        </div>
                        <div class="stat-icon sent">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-value">₱<?php echo number_format($total_received, 2); ?></div>
                            <div class="stat-label">Total Received</div>
                        </div>
                        <div class="stat-icon received">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Transactions -->
                <div class="card-modern">
                    <div class="card-header">
                        <h3 class="card-title">Recent Transactions</h3>
                        <a href="transactionhistory.php" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <ul class="transaction-list">
                        <?php 
                        if(mysqli_num_rows($recent_transactions) > 0) {
                            while($transaction = mysqli_fetch_assoc($recent_transactions)) {
                                $is_sender = ($transaction['sender'] == $user_name);
                                $other_party = $is_sender ? $transaction['receiver'] : $transaction['sender'];
                                $amount = $transaction['balance'];
                                $date = date('M d, Y', strtotime($transaction['datetime']));
                        ?>
                        <li class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-icon <?php echo $is_sender ? 'sent' : 'received'; ?>">
                                    <i class="fas fa-<?php echo $is_sender ? 'arrow-up' : 'arrow-down'; ?>"></i>
                                </div>
                                <div class="transaction-details">
                                    <h4><?php echo $is_sender ? 'Sent to' : 'Received from'; ?> <?php echo htmlspecialchars($other_party); ?></h4>
                                    <p><?php echo $date; ?></p>
                                </div>
                            </div>
                            <div class="transaction-amount <?php echo $is_sender ? 'negative' : 'positive'; ?>">
                                <?php echo $is_sender ? '-' : '+'; ?>₱<?php echo number_format($amount, 2); ?>
                            </div>
                        </li>
                        <?php 
                            }
                        } else {
                            echo '<li class="transaction-item"><p>No transactions yet</p></li>';
                        }
                        ?>
                    </ul>
                </div>

                <!-- Quick Actions -->
                <div class="card-modern">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    
                    <div class="quick-actions">
                        <a href="transfermoney.php" class="action-btn action-btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            <span>Send Money</span>
                        </a>
                        
                        <a href="transactionhistory.php" class="action-btn action-btn-outline">
                            <i class="fas fa-history"></i>
                            <span>View History</span>
                        </a>
                        
                        <a href="createuser.php" class="action-btn action-btn-outline">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Contact</span>
                        </a>
                        
                        <a href="settings.php" class="action-btn action-btn-outline">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        
                        <a href="index.php" class="action-btn action-btn-outline">
                            <i class="fas fa-home"></i>
                            <span>Home Page</span>
                        </a>
                    </div>
                    
                    <div style="margin-top: 2rem; padding: 1rem; background: linear-gradient(135deg, rgba(0, 139, 139, 0.1), rgba(47, 79, 79, 0.1)); border-radius: 12px;">
                        <h4 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 0.5rem;">Account Info</h4>
                        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                            <strong>ID:</strong> <?php echo htmlspecialchars($user_data['user_id']); ?>
                        </p>
                        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                            <strong>Email:</strong> <?php echo htmlspecialchars($user_data['user_email']); ?>
                        </p>
                        <p style="font-size: 0.85rem; color: var(--text-secondary);">
                            <strong>Phone:</strong> <?php echo htmlspecialchars($user_data['phone']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/loading.js"></script>
</body>
</html>
