<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enable Notifications - OBI Banking</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/modern-style.css">
    
    <style>
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .setup-container {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        
        .setup-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }
        
        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .features-list {
            text-align: left;
            margin: 2rem 0;
            padding: 1.5rem;
            background: var(--light-color);
            border-radius: 12px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .feature-item:last-child {
            margin-bottom: 0;
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-icon">
            <i class="fas fa-bell"></i>
        </div>
        
        <h1>Enable Advanced Features</h1>
        <p>Unlock notifications, settings, and transaction export features with one click!</p>
        
        <div class="features-list">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-check"></i></div>
                <div>
                    <strong>Real-time Notifications</strong><br>
                    <small>Get notified for all transactions</small>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-check"></i></div>
                <div>
                    <strong>Advanced Settings</strong><br>
                    <small>Update profile and manage account</small>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-check"></i></div>
                <div>
                    <strong>Export Transactions</strong><br>
                    <small>Download as CSV, PDF, or Excel</small>
                </div>
            </div>
        </div>
        
        <form method="post" action="setup_notifications.php">
            <button type="submit" class="btn-modern btn-primary" style="width: 100%; padding: 1.25rem; font-size: 1.1rem;">
                <i class="fas fa-rocket"></i> Enable Features Now
            </button>
        </form>
        
        <div style="margin-top: 1.5rem;">
            <a href="PHP_Dashboard_Modern.php" style="color: var(--text-secondary); text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Skip for now
            </a>
        </div>
    </div>
    
    <script src="assets/js/loading.js"></script>
</body>
</html>
