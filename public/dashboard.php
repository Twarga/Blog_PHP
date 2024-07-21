<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user posts from the database
$sql = "SELECT id, title, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC";
if ($stmt = $link->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <nav>
        <a href="create_post.php">Create New Post</a> |
        <a href="view_posts.php">View All Posts</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <h2>Your Posts</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                    <em>By you on <?php echo htmlspecialchars($row['created_at']); ?></em>
                    <p><?php
                        // Displaying a snippet of the post body
                        $body_query = "SELECT LEFT(body, 100) as body_snippet FROM posts WHERE id = ?";
                        if ($body_stmt = $link->prepare($body_query)) {
                            $body_stmt->bind_param("i", $row['id']);
                            $body_stmt->execute();
                            $body_result = $body_stmt->get_result();
                            $body_row = $body_result->fetch_assoc();
                            echo htmlspecialchars($body_row['body_snippet']) . '...';
                            $body_stmt->close();
                        }
                    ?></p>
                    <a href="edit_post.php?id=<?php echo htmlspecialchars($row['id']); ?>">Edit</a>
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
