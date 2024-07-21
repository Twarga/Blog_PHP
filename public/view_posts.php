<?php
// Include database configuration
require_once '../config/db.php';

// Fetch posts from the database
$sql = "SELECT posts.id, posts.title, posts.body, posts.created_at, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";

if ($result = $link->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
            echo "<p><em>By " . htmlspecialchars($row['username']) . " on " . htmlspecialchars($row['created_at']) . "</em></p>";
            echo "<p>" . htmlspecialchars($row['body']) . "</p>";
            echo "<hr>";
        }
    } else {
        echo "No posts found.";
    }

    $result->free();
} else {
    echo "Error: " . $link->error;
}

$link->close();
?>
