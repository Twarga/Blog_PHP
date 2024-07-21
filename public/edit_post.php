<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $body = $_POST['body'];

    $sql = "UPDATE posts SET title = ?, body = ? WHERE id = ? AND user_id = ?";
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param("ssii", $title, $body, $post_id, $user_id);

        if ($stmt->execute()) {
            echo "Post updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $link->error;
    }
} else {
    $sql = "SELECT title, body FROM posts WHERE id = ? AND user_id = ?";
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $post = $result->fetch_assoc();
        } else {
            echo "Post not found.";
            exit;
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
    <title>Edit Post</title>
</head>
<body>
    <h2>Edit Post</h2>
    <form action="edit_post.php?id=<?php echo htmlspecialchars($post_id); ?>" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>

        <label for="body">Body:</label>
        <textarea id="body" name="body" rows="5" required><?php echo htmlspecialchars($post['body']); ?></textarea><br><br>

        <button type="submit">Update Post</button>
    </form>
</body>
</html>
