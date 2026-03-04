<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Money - OBI Banking</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/modern-style.css">

    <style>
        body {
            background: #f5f7fa;
        }
        
        .transfer-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .transfer-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .users-table {
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
        
        .btn-proceed {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-proceed:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
    </style>
</head>

<body>
    <div class="loading-screen">
        <div class="loader"></div>
        <div class="loading-text">Loading...</div>
    </div>
<?php
    include 'config.php';
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn,$sql);
?>

    <div class="transfer-container">
        <div class="transfer-header">
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-exchange-alt"></i> Transfer Money
            </h1>
            <p style="color: var(--text-secondary);">Select a recipient to send money</p>
            <div style="margin-top: 1rem;">
                <a href="PHP_Dashboard_Modern.php" class="btn-modern btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <div class="users-table">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">Select Recipient</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>E-Mail</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php 
                    $cnt=1;
                    while($rows=mysqli_fetch_assoc($result)){
                ?>
                    <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo htmlspecialchars($rows['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($rows['user_email']); ?></td>
                        <td>₱<?php echo number_format($rows['balance'], 2); ?></td>
                        <td>
                            <a href="selecteduserdetail.php?id=<?php echo $rows['id']; ?>">
                                <button class="btn-proceed">
                                    <i class="fas fa-paper-plane"></i> Proceed
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php
                $cnt=$cnt+1;
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