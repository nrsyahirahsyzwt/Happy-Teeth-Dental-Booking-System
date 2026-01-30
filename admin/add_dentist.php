<?php
session_start();
include "../config/db.php";
include "../includes/header.php";
include "../includes/admin_sidebar.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

/* HANDLE SUBMIT */
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);

    mysqli_query($conn, "
        INSERT INTO dentists (fullname, email, phone)
        VALUES ('$fullname', '$email', '$phone')
    ");

    $success = true;
}
?>

<div class="main-content">

<h4 class="mb-3">🧑‍⚕️ Add Dentist</h4>

<?php if ($success): ?>
<div class="alert alert-success">
    Dentist added successfully.
</div>
<?php endif; ?>

<div class="card shadow p-4" style="max-width:600px;">

<form method="POST">

    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="fullname" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control">
    </div>

    <button class="btn btn-success">Add Dentist</button>
    <a href="user_management.php" class="btn btn-secondary ms-2">Back</a>

</form>
</div>
</div>

<?php include "../includes/footer.php"; ?>
