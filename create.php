<?php
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$title = $content = "";
$title_err = $content_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_title = trim($_POST["title"]);
    if (empty($input_title)) {
        $title_err = "Please enter a title.";
    } else {
        $title = $input_title;
    }

    $input_content = trim($_POST["content"]);
    if (empty($input_content)) {
        $content_err = "Please enter some content.";
    } else {
        $content = $input_content;
    }

    if (empty($title_err) && empty($content_err)) {
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Post</title>
</head>

<body>
    <h2>Create New Post</h2>
    <form action="create.php" method="post">
        <div>
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
            <span style="color:red;">
                <?php echo $title_err; ?>
            </span>
        </div>
        <div>
            <label>Content</label>
            <textarea name="content" rows="5" cols="40"><?php echo htmlspecialchars($content); ?></textarea>
            <span style="color:red;">
                <?php echo $content_err; ?>
            </span>
        </div>
        <div>
            <input type="submit" value="Submit">
            <a href="index.php">Cancel</a>
        </div>
    </form>
</body>

</html>