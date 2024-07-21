<?php
require_once '../config/db.php';
session_start();

// Ensure user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO posts (title, body, user_id) VALUES (?, ?, ?)";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("ssi", $title, $body, $user_id);

    if ($stmt->execute()) {
        echo "Post created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
</head>
<body>
    <h2>Create New Post</h2>
    <form action="create_post.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="body">Body:</label>
        <textarea id="body" name="body" required></textarea><br><br>
        
        <button type="submit">Create Post</button>
    </form>
</body>
</html>
