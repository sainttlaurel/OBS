<?php 
session_start();
include("PHP_Connection.php");
include("PHP_Functions.php");

$user_data = check_login($con);
$user_name = $user_data['user_name'];
$format = isset($_GET['format']) ? $_GET['format'] : 'csv';

// Get all transactions
$query = "SELECT * FROM transaction WHERE sender = ? OR receiver = ? ORDER BY datetime DESC";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ss", $user_name, $user_name);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$transactions = [];
while($row = mysqli_fetch_assoc($result)) {
    $transactions[] = $row;
}

// Export as CSV
if($format == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="transactions_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Header
    fputcsv($output, ['Transaction ID', 'Type', 'From/To', 'Amount', 'Date & Time']);
    
    // Data
    foreach($transactions as $transaction) {
        $is_sender = ($transaction['sender'] == $user_name);
        $type = $is_sender ? 'Sent' : 'Received';
        $party = $is_sender ? $transaction['receiver'] : $transaction['sender'];
        
        fputcsv($output, [
            $transaction['sno'],
            $type,
            $party,
            '₱' . number_format($transaction['balance'], 2),
            date('M d, Y h:i A', strtotime($transaction['datetime']))
        ]);
    }
    
    fclose($output);
    exit;
}

// Export as Excel (HTML table that Excel can open)
if($format == 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="transactions_' . date('Y-m-d') . '.xls"');
    
    echo '<html>';
    echo '<head><meta charset="UTF-8"></head>';
    echo '<body>';
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>Transaction ID</th>';
    echo '<th>Type</th>';
    echo '<th>From/To</th>';
    echo '<th>Amount</th>';
    echo '<th>Date & Time</th>';
    echo '</tr>';
    
    foreach($transactions as $transaction) {
        $is_sender = ($transaction['sender'] == $user_name);
        $type = $is_sender ? 'Sent' : 'Received';
        $party = $is_sender ? $transaction['receiver'] : $transaction['sender'];
        
        echo '<tr>';
        echo '<td>' . $transaction['sno'] . '</td>';
        echo '<td>' . $type . '</td>';
        echo '<td>' . htmlspecialchars($party) . '</td>';
        echo '<td>₱' . number_format($transaction['balance'], 2) . '</td>';
        echo '<td>' . date('M d, Y h:i A', strtotime($transaction['datetime'])) . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</body>';
    echo '</html>';
    exit;
}

// Export as PDF (HTML that can be printed as PDF)
if($format == 'pdf') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Transaction History - <?php echo htmlspecialchars($user_name); ?></title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 20px;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #333;
                padding-bottom: 20px;
            }
            .info {
                margin-bottom: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }
            th {
                background-color: var(--primary-color);
                color: white;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .sent {
                color: #ef4444;
            }
            .received {
                color: #10b981;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 12px;
                color: #666;
            }
            @media print {
                button {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>OBI Banking - Transaction History</h1>
            <p>Account: <?php echo htmlspecialchars($user_name); ?></p>
            <p>Generated: <?php echo date('F d, Y h:i A'); ?></p>
        </div>
        
        <div class="info">
            <strong>Account ID:</strong> <?php echo htmlspecialchars($user_data['user_id']); ?><br>
            <strong>Email:</strong> <?php echo htmlspecialchars($user_data['user_email']); ?><br>
            <strong>Current Balance:</strong> ₱<?php echo number_format($user_data['balance'], 2); ?><br>
            <strong>Total Transactions:</strong> <?php echo count($transactions); ?>
        </div>
        
        <button onclick="window.print()" style="padding: 10px 20px; background: var(--primary-color); color: white; border: none; border-radius: 5px; cursor: pointer; margin-bottom: 20px;">
            Print / Save as PDF
        </button>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>From/To</th>
                    <th>Amount</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transactions as $transaction): 
                    $is_sender = ($transaction['sender'] == $user_name);
                    $type = $is_sender ? 'Sent' : 'Received';
                    $party = $is_sender ? $transaction['receiver'] : $transaction['sender'];
                    $class = $is_sender ? 'sent' : 'received';
                ?>
                <tr>
                    <td><?php echo $transaction['sno']; ?></td>
                    <td class="<?php echo $class; ?>"><?php echo $type; ?></td>
                    <td><?php echo htmlspecialchars($party); ?></td>
                    <td class="<?php echo $class; ?>">
                        <?php echo $is_sender ? '-' : '+'; ?>₱<?php echo number_format($transaction['balance'], 2); ?>
                    </td>
                    <td><?php echo date('M d, Y h:i A', strtotime($transaction['datetime'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="footer">
            <p>This is an official transaction history from OBI Banking System</p>
            <p>© 2024 OBI Banking. All rights reserved.</p>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
