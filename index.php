<?php
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Blog - Home</title>
</head>

<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["user"]); ?>!</h2>
    <p>
        <a href="create.php">Create New Post</a> |
        <a href="logout.php">Logout</a>
    </p>

    <h3>All Posts</h3>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>";
            echo "<h4>" . htmlspecialchars($row["title"]) . "</h4>";
            echo "<p>" . nl2br(htmlspecialchars($row["content"])) . "</p>";
            echo "<small>Posted on: " . $row["created_at"] . "</small><br>";
            echo "<a href='edit.php?id=" . $row["id"] . "'>Edit</a> | ";
            echo "<a href='delete.php?id=" . $row["id"] . "' onclick=\"return confirm('Are you sure you want to delete this post?');\">Delete</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No posts found.</p>";
    }
    ?>
</body>

</html>