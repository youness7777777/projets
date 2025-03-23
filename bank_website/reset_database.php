<?php
require_once "config.php";

// Drop existing users table
$sql = "DROP TABLE IF EXISTS users";
mysqli_query($conn, $sql);

// Create new users table
$sql = "CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql);

// Insert a test user with plain text password
$sql = "INSERT INTO users (username, password, email) VALUES ('test', 'test123', 'test@test.com')";
if(mysqli_query($conn, $sql)) {
    echo "Database reset successfully. You can now login with:<br>";
    echo "Username: test<br>";
    echo "Password: test123";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
