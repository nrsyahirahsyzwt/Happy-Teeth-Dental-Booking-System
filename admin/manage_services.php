<?php
include "../config/db.php";
include "../includes/header.php";
include "../includes/admin_sidebar.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $price        = (float) $_POST['price'];
    $duration     = (int) $_POST['duration'];

    mysqli_query($conn, "
        INSERT INTO services (service_name, description, price, duration)
        VALUES ('$service_name', '$description', $price, $duration)
    ");

    $success = true;
}
?>

<div class="main-content">

<h4 class="mb-3">➕ Add Service</h4>

<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        Service successfully added.
    </div>
<?php endif; ?>

<div class="card shadow p-4" style="max-width:600px;">
    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Service Name</label>
            <input type="text" name="service_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (RM)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Duration (Minutes)</label>
            <input type="number" name="duration" class="form-control" required>
        </div>

        <button class="btn btn-success">
            Add Service
        </button>

        <a href="dashboard.php" class="btn btn-secondary ms-2">
            Back
        </a>

    </form>
</div>

</div>

<?php include "../includes/footer.php"; ?>
