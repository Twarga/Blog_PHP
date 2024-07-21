<?php
require_once '../config/db.php';
session_start();

// Fetch all posts from the database
$sql = "SELECT id, title, body, created_at, user_id FROM posts ORDER BY created_at DESC";
if ($stmt = $link->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();
}
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

    <?php if ($result && $result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p><?php echo htmlspecialchars($row['body']); ?></p>
                    <em>By <?php echo htmlspecialchars($row['user_id']); ?> on <?php echo htmlspecialchars($row['created_at']); ?></em>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="edit_post.php?id=<?php echo htmlspecialchars($row['id']); ?>">Edit</a>
                        <a href="delete_post.php?id=<?php echo htmlspecialchars($row['id']); ?>">Delete</a>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>
    
    <?php $stmt->close(); ?>
    <?php $link->close(); ?>
</body>
</html>
