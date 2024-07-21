<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database configuration
require_once '../config/db.php';  // Corrected path to db.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Prepare and execute SQL statement
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param("sss", $user, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $link->error;
    }
}

// Close database connection
$link->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit">Register</button>
    </form>
</body>
</html>
