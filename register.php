<?php
require_once "config.php";

$username = $password = "";
$username_err = $password_err = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username)) {
        $username_err = "Please enter a username.";
    } elseif (strlen($username) < 3) {
        $username_err = "Username must have at least 3 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $username_err = "Username already taken.";
        }
        $stmt->close();
    }

    if (empty($password)) {
        $password_err = "Please enter a password.";
    } elseif (strlen($password) < 6) {
        $password_err = "Password must have at least 6 characters.";
    }

    if (empty($username_err) && empty($password_err)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php?msg=" . urlencode("Registration successful, please login."));
            exit();
        } else {
            $error = "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}

include 'header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 border rounded p-4 shadow-sm bg-light">
        <h2 class="mb-4">Register</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="register.php" method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <!-- Client side validation: required, minlength, pattern -->
                <input type="text" name="username"
                    class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo htmlspecialchars($username); ?>" required minlength="3" pattern="^[a-zA-Z0-9_]+$">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <!-- Client side validation: required, minlength -->
                <input type="password" name="password"
                    class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required
                    minlength="6">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>

            <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>