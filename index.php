<?php
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

if (!empty($search)) {
    $search_param = "%$search%";
    $count_stmt = $conn->prepare("SELECT COUNT(id) AS total FROM posts WHERE title LIKE ? OR content LIKE ?");
    $count_stmt->bind_param("ss", $search_param, $search_param);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_rows = $count_result->fetch_assoc()['total'];
    $count_stmt->close();

    $stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $search_param, $search_param, $limit, $offset);
} else {
    $count_query = "SELECT COUNT(id) AS total FROM posts";
    $count_result = $conn->query($count_query);
    $total_rows = $count_result->fetch_assoc()['total'];

    $stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$total_pages = ceil($total_rows / $limit);

include 'header.php';
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2>Blog Posts</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="create.php" class="btn btn-primary">Create New Post</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="index.php" method="get" class="row gx-3 gy-2 align-items-center">
            <div class="col-sm-9">
                <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-sm-3">
                <button type="submit" class="btn btn-secondary w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<?php if ($result->num_rows > 0): ?>
    <div class="row">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-md-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($row["title"]); ?></h4>
                        <p class="card-text text-muted"><small>Posted on: <?php echo $row["created_at"]; ?></small></p>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
                        <hr>
                        <a href="edit.php?id=<?php echo $row["id"]; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="delete.php?id=<?php echo $row["id"]; ?>" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

<?php else: ?>
    <div class="alert alert-info">No posts found.</div>
<?php endif; ?>

<?php
$stmt->close();
include 'footer.php';
?>