<?php
require_once "config.php";

// Update all existing passwords to a default plain text password
$sql = "UPDATE users SET password = 'password123'";
if(mysqli_query($conn, $sql)) {
    echo "All passwords have been updated to plain text successfully.";
} else {
    echo "Error updating passwords: " . mysqli_error($conn);
}
?>
