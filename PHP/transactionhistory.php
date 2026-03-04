<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - OBI Banking</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/modern-style.css">
    
    <style>
        body {
            background: #f5f7fa;
        }
        
        .history-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .history-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .history-table {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        tr:hover {
            background: var(--light-color);
        }
        
        .amount-sent {
            color: var(--danger-color);
            font-weight: 600;
        }
        
        .amount-received {
            color: var(--success-color);
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="loading-screen">
        <div class="loader"></div>
        <div class="loading-text">Loading History...</div>
    </div>

    <div class="history-container">
        <div class="history-header">
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-history"></i> Transaction History
            </h1>
            <p style="color: var(--text-secondary);">View all your past transactions</p>
            <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                <a href="PHP_Dashboard_Modern.php" class="btn-modern btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="settings.php" class="btn-modern btn-primary">
                    <i class="fas fa-download"></i> Export Transactions
                </a>
            </div>
        </div>

        <div class="history-table">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">All Transactions</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Amount</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
        <?php

            include 'config.php';

            $sql ="SELECT * FROM transaction";

            $query =mysqli_query($conn, $sql);

            while($rows = mysqli_fetch_assoc($query))
            {
        ?>

            <tr>
                <td><?php echo $rows['sno']; ?></td>
                <td><?php echo htmlspecialchars($rows['sender']); ?></td>
                <td><?php echo htmlspecialchars($rows['receiver']); ?></td>
                <td class="amount-sent">₱<?php echo number_format($rows['balance'], 2); ?></td>
                <td><?php echo date('M d, Y h:i A', strtotime($rows['datetime'])); ?></td>
            </tr>
                
        <?php
            }

        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 3rem; padding: 2rem; color: var(--text-secondary);">
        <p>2024 © OBI Banking. All rights reserved.</p>
    </footer>

    <script src="assets/js/loading.js"></script>
</body>
</html>