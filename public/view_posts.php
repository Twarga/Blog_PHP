<?php
require_once '../config/db.php';
session_start();

// Fetch all posts
$sql = "SELECT * FROM posts";
$result = $link->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
</head>
<body>
    <h1>All Posts</h1>
    <nav>
        <a href="dashboard.php">Dashboard</a> |
        <a href="create_post.php">Create New Post</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <?php
    if ($result->num_rows > 0) {
        while ($post = $result->fetch_assoc()) {
            echo "<h2>" . htmlspecialchars($post['title']) . "</h2>";
            echo "<p>By " . htmlspecialchars($post['author']) . " on " . htmlspecialchars($post['date']) . "</p>";
            echo "<p>" . htmlspecialchars($post['body']) . "</p>";

            // Display edit and delete links if the user is an admin or the author
            if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_id'] == $post['author_id'])) {
                echo "<a href='edit_post.php?id=" . $post['id'] . "'>Edit</a> | ";
                echo "<a href='delete_post.php?id=" . $post['id'] . "'>Delete</a>";
            }
            echo "<hr>";
        }
    } else {
        echo "<p>No posts found.</p>";
    }

    // Close database connection
    $link->close();
    ?>
</body>
</html>
