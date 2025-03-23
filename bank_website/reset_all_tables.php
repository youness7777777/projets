<?php
require_once "config.php";

// First drop tables in correct order (child tables first)
$sql = "DROP TABLE IF EXISTS transactions";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS accounts";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS users";
mysqli_query($conn, $sql);

// Recreate users table without foreign key constraints
$sql = "CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql);

// Recreate accounts table without foreign key constraints
$sql = "CREATE TABLE accounts (
    account_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    account_number VARCHAR(20) UNIQUE NOT NULL,
    balance DECIMAL(10,2) DEFAULT 0.00,
    account_type ENUM('Savings', 'Checking') NOT NULL
)";
mysqli_query($conn, $sql);

// Recreate transactions table without foreign key constraints
$sql = "CREATE TABLE transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    account_id INT,
    type ENUM('deposit', 'withdrawal', 'transfer') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql);

// Insert a test user
$sql = "INSERT INTO users (username, password, email) VALUES ('test', 'test123', 'test@test.com')";
if(mysqli_query($conn, $sql)) {
    $user_id = mysqli_insert_id($conn);
    
    // Create a test account for the user
    $account_number = sprintf('%011d', mt_rand(0, 99999999999));
    $sql = "INSERT INTO accounts (user_id, account_number, account_type) VALUES ($user_id, '$account_number', 'Savings')";
    mysqli_query($conn, $sql);
    
    echo "Database reset successfully. All foreign key constraints removed.<br>";
    echo "You can now login with:<br>";
    echo "Username: test<br>";
    echo "Password: test123";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
