<?php
include "../config/db.php";
include "../includes/header.php";
include "../includes/admin_sidebar.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: user_management.php");
    exit;
}

$user_id = (int) $_GET['id'];

/* FETCH USER */
$result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: user_management.php");
    exit;
}

/* HANDLE UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first  = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last   = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    mysqli_query($conn, "
        UPDATE users SET
            first_name = '$first',
            last_name  = '$last',
            email      = '$email',
            gender     = '$gender'
        WHERE user_id = $user_id
    ");

    header("Location: user_management.php?updated=1");
    exit;
}
?>

<div class="main-content p-4">

<h4 class="fw-bold mb-4">
    <i class="bi bi-pencil-square me-2"></i>
    Edit User
</h4>

<div class="card shadow p-4" style="max-width:600px;">
<form method="POST">

    <div class="mb-3">
        <label class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control"
               value="<?= htmlspecialchars($user['first_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control"
               value="<?= htmlspecialchars($user['last_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
               value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select" required>
            <option value="Male"   <?= $user['gender']=='Male'?'selected':'' ?>>Male</option>
            <option value="Female" <?= $user['gender']=='Female'?'selected':'' ?>>Female</option>
        </select>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-success">
            <i class="bi bi-check-circle me-1"></i> Save Changes
        </button>

        <a href="user_management.php" class="btn btn-secondary">
            Cancel
        </a>
    </div>

</form>
</div>
</div>

<?php include "../includes/footer.php"; ?>
