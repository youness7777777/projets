<?php
require_once "config.php";

$transfer_err = "";
$success_message = "";

// Vulnerable - no session check
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 1;

// Get user's accounts - vulnerable to SQL injection
$accounts = array();
$sql = "SELECT * FROM accounts WHERE user_id = " . $user_id;
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $accounts[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Directly use POST data - vulnerable to SQL injection
    $from_account = $_POST["from_account"];
    $to_account = $_POST["to_account"];
    $amount = $_POST["amount"];
    $description = $_POST["description"];

    // Very vulnerable query
    $sql = "SELECT balance FROM accounts WHERE account_id = $from_account";
    $result = mysqli_query($conn, $sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $balance = $row['balance'];
        
        if ($balance >= $amount) {
            // Vulnerable update queries
            $sql = "UPDATE accounts SET balance = balance - $amount WHERE account_id = $from_account";
            mysqli_query($conn, $sql);
            
            $sql = "UPDATE accounts SET balance = balance + $amount WHERE account_id = $to_account";
            mysqli_query($conn, $sql);
            
            // Vulnerable insert
            $sql = "INSERT INTO transactions (account_id, type, amount, description) 
                   VALUES ($from_account, 'transfer', $amount, '$description')";
            mysqli_query($conn, $sql);
            
            $success_message = "Transfer completed successfully!";
        } else {
            $transfer_err = "Insufficient balance.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Money - SecureBank</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1>SecureBank</h1>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="transfer.php" class="active">Transfer</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Transfer Money</h2>
            <?php if(!empty($transfer_err)) { ?>
                <div class="error-message"><?php echo $transfer_err; ?></div>
            <?php } ?>
            <?php if(!empty($success_message)) { ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php } ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="from_account">From Account</label>
                    <select id="from_account" name="from_account" required>
                        <?php foreach ($accounts as $account): ?>
                            <option value="<?php echo $account['account_id']; ?>">
                                <?php echo $account['account_type']; ?> - $<?php echo number_format($account['balance'], 2); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="to_account">To Account</label>
                    <select id="to_account" name="to_account" required>
                        <?php foreach ($accounts as $account): ?>
                            <option value="<?php echo $account['account_id']; ?>">
                                <?php echo $account['account_type']; ?> - <?php echo $account['account_number']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount ($)</label>
                    <input type="text" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="description">Description (Optional)</label>
                    <input type="text" id="description" name="description">
                </div>
                <button type="submit" class="form-submit">Transfer Money</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="footer-bottom">
            <p>&copy; 2025 SecureBank. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Prevent selecting the same account for source and destination
        document.getElementById('from_account').addEventListener('change', function() {
            const toAccount = document.getElementById('to_account');
            for (let option of toAccount.options) {
                option.disabled = option.value === this.value;
            }
        });

        document.getElementById('to_account').addEventListener('change', function() {
            const fromAccount = document.getElementById('from_account');
            for (let option of fromAccount.options) {
                option.disabled = option.value === this.value;
            }
        });
    </script>
</body>
</html>

<!-- Added comment to show SQL injection hints -->
<!-- Try: 
1. In user_id parameter: 1 OR 1=1
2. In amount: 100 OR 1=1
3. In description: '; DROP TABLE transactions; --
-->
