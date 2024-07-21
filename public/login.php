<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database configuration
require_once '../config/db.php';  // Corrected path to db.php

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form fields are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare SQL query to prevent SQL injection
        if ($stmt = $link->prepare("SELECT id, password FROM users WHERE email = ?")) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Password is correct
                    echo "Login successful!";
                    // Start session or perform other actions
                } else {
                    // Password is incorrect
                    echo "Invalid password.";
                }
            } else {
                // User not found
                echo "User not found.";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $link->error;
        }
    } else {
        echo "Please fill in both fields.";
    }
} else {
    // Display the login form if not a POST request
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>
    <body>
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            
            <button type="submit">Login</button>
        </form>
    </body>
    </html>
    <?php
}

// Close database connection
$link->close();
?>
