<?php
// Include database configuration
require_once '../config/db.php';

// Get post ID from URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch post details from the database
$sql = "SELECT posts.title, posts.body, posts.created_at, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.id = ?";

if ($stmt = $link->prepare($sql)) {
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $post = $result->fetch_assoc();
        echo "<h2>" . htmlspecialchars($post['title']) . "</h2>";
        echo "<p><em>By " . htmlspecialchars($post['username']) . " on " . htmlspecialchars($post['created_at']) . "</em></p>";
        echo "<p>" . htmlspecialchars($post['body']) . "</p>";
    } else {
        echo "Post not found.";
    }

    $stmt->close();
} else {
    echo "Error preparing statement: " . $link->error;
}

$link->close();
?>
