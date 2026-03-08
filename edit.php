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

    $title = htmlspecialchars(trim($_POST["title"]));
    if (empty($title)) {
        $title_err = "Please enter a title.";
    } elseif (strlen($title) < 5) {
        $title_err = "Title must be at least 5 characters.";
    }

    $content = htmlspecialchars(trim($_POST["content"]));
    if (empty($content)) {
        $content_err = "Please enter some content.";
    } elseif (strlen($content) < 10) {
        $content_err = "Content must be at least 10 characters.";
    }

    if (empty($title_err) && empty($content_err)) {
        $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $content, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Oops! Something went wrong. Please try again later.</div>";
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
            echo "<div class='alert alert-danger'>Oops! Something went wrong. Please try again later.</div>";
        }
        $stmt->close();
    } else {
        header("Location: error.php");
        exit();
    }
}

include 'header.php';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2>Edit Post</h2>
        <form action="edit.php" method="post" class="mt-4">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" />

            <div class="mb-3">
                <label class="form-label">Title</label>
                <!-- Client side validation: required, minlength -->
                <input type="text" name="title"
                    class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($title); ?>" required minlength="5">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <!-- Client side validation: required, minlength -->
                <textarea name="content" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>"
                    rows="5" required minlength="10"><?php echo htmlspecialchars($content); ?></textarea>
                <span class="invalid-feedback"><?php echo $content_err; ?></span>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>