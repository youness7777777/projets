<?php
session_start();
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Directly using user input without sanitization (insecure)
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Vulnerable query with no protection against SQL injection
    $sql = "SELECT id, username, password FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $row['id'];
        $_SESSION["username"] = $row['username'];
        
        header("location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SecureBank</title>
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
                <li><a href="index.html">Home</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Login to Your Account</h2>
            <?php if(!empty($error)) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="form-submit">Login</button>
            </form>
            <p class="form-footer">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </div>
    </main>

    <footer>
        <div class="footer-bottom">
            <p>&copy; 2025 SecureBank. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
