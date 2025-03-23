<?php
require_once "config.php";

$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST["username"])) {
        $username_err = "Please enter a username.";
    } else {
        $username = $_POST["username"];
        $sql = "SELECT id FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $username_err = "This username is already taken.";
        }
    }
    
    // Validate email
    if (empty($_POST["email"])) {
        $email_err = "Please enter an email.";
    } else {
        $email = $_POST["email"];
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $email_err = "This email is already registered.";
        }
    }
    
    // Validate password
    if (empty($_POST["password"])) {
        $password_err = "Please enter a password.";     
    } else {
        $password = $_POST["password"];
    }
    
    // Validate confirm password
    if (empty($_POST["confirm_password"])) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = $_POST["confirm_password"];
        if ($password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        if (mysqli_query($conn, $sql)) {
            header("location: login.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SecureBank</title>
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
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Create an Account</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                    <span class="error-message"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                    <span class="error-message"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error-message"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <span class="error-message"><?php echo $confirm_password_err; ?></span>
                </div>
                <button type="submit" class="form-submit">Register</button>
            </form>
            <p class="form-footer">
                Already have an account? <a href="login.php">Login here</a>
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
