<?php
include "../config/db.php";
include "../includes/header.php";
include "../includes/admin_sidebar.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role       = "Staff";

    mysqli_query($conn, "
        INSERT INTO users (first_name, last_name, email, password, role)
        VALUES ('$first_name', '$last_name', '$email', '$password', '$role')
    ");

    $success = true;
}
?>

<div class="main-content">

<h4 class="mb-3">➕ Add Staff</h4>

<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        Staff successfully added.
    </div>
<?php endif; ?>

<div class="card shadow p-4" style="max-width:600px;">
    <form method="POST">

        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-success">
            Add Staff
        </button>

        <a href="user_management.php" class="btn btn-secondary ms-2">
            Back
        </a>

    </form>
</div>

</div>

<?php include "../includes/footer.php"; ?>
