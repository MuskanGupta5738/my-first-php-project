<?php
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$title = $content = "";
$title_err = $content_err = "";

if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $id = $_POST["id"];

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
        $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $content, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
} else {
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);

        $stmt = $conn->prepare("SELECT id, title, content FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $title = $row["title"];
                $content = $row["content"];
            } else {
                header("Location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    } else {
        header("Location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Post</title>
</head>

<body>
    <h2>Edit Post</h2>
    <form action="edit.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" />
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
            <input type="submit" value="Update">
            <a href="index.php">Cancel</a>
        </div>
    </form>
</body>

</html>