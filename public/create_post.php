<?php
// Include database configuration and session management
require_once '../config/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $title = $_POST['title'];
    $body = $_POST['body'];
    $user_id = $_SESSION['user_id'];

    // Prepare and execute SQL statement
    $sql = "INSERT INTO posts (title, body, user_id) VALUES (?, ?, ?)";
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param("ssi", $title, $body, $user_id);

        if ($stmt->execute()) {
            echo "Post created successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $link->error;
    }
}

$link->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
</head>
<body>
    <h2>Create a New Post</h2>
    <form action="create_post.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="body">Body:</label>
        <textarea id="body" name="body" rows="5" required></textarea><br><br>

        <button type="submit">Create Post</button>
    </form>
</body>
</html>
