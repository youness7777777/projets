<?php
require_once "config.php";

// Get user's accounts
$user_id = 1; // Default to first user
$accounts = array();
$sql = "SELECT * FROM accounts WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $accounts[] = $row;
}

// Get recent transactions
$transactions = array();
if (!empty($accounts)) {
    $account_ids = array_column($accounts, 'account_id');
    $account_ids_str = implode(',', $account_ids);
    $sql = "SELECT * FROM transactions WHERE account_id IN ($account_ids_str) ORDER BY transaction_date DESC LIMIT 5";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SecureBank</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard {
            padding: 8rem 5% 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }
        .account-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .transaction-list {
            background: var(--white);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .transaction-item {
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        .transaction-item:last-child {
            border-bottom: none;
        }
        .balance {
            font-size: 2rem;
            color: var(--primary-color);
            margin: 1rem 0;
        }
        .account-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            background: var(--primary-color);
            color: var(--white);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .action-btn:hover {
            background-color: #1557b0;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1>SecureBank</h1>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="transfer.php"><i class="fas fa-exchange-alt"></i> Transfer</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard">
        <h2>Welcome!</h2>
        
        <div class="dashboard-grid">
            <div class="accounts-section">
                <h3>Your Accounts</h3>
                <?php foreach ($accounts as $account): ?>
                    <div class="account-card">
                        <h4><?php echo htmlspecialchars($account["account_type"]); ?> Account</h4>
                        <p>Account Number: <?php echo htmlspecialchars($account["account_number"]); ?></p>
                        <div class="balance">$<?php echo number_format($account["balance"], 2); ?></div>
                        <div class="account-actions">
                            <button class="action-btn" onclick="location.href='deposit.php?account=<?php echo $account["account_id"]; ?>'">
                                <i class="fas fa-plus"></i> Deposit
                            </button>
                            <button class="action-btn" onclick="location.href='withdraw.php?account=<?php echo $account["account_id"]; ?>'">
                                <i class="fas fa-minus"></i> Withdraw
                            </button>
                            <button class="action-btn" onclick="location.href='transfer.php?from=<?php echo $account["account_id"]; ?>'">
                                <i class="fas fa-exchange-alt"></i> Transfer
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="transactions-section">
                <h3>Recent Transactions</h3>
                <div class="transaction-list">
                    <?php if (empty($transactions)): ?>
                        <p>No recent transactions.</p>
                    <?php else: ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <div class="transaction-item">
                                <p>
                                    <strong><?php echo ucfirst($transaction["type"]); ?></strong>
                                    <span class="amount">$<?php echo number_format($transaction["amount"], 2); ?></span>
                                </p>
                                <p class="transaction-date">
                                    <?php echo date("M j, Y H:i", strtotime($transaction["transaction_date"])); ?>
                                </p>
                                <?php if ($transaction["description"]): ?>
                                    <p class="transaction-description"><?php echo htmlspecialchars($transaction["description"]); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-bottom">
            <p>&copy; 2025 SecureBank. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
