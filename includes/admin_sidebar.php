<?php
include "../config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$current = basename($_SERVER['PHP_SELF']);

$admin = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT first_name, last_name, photo FROM users WHERE user_id = $user_id LIMIT 1"
));

$uploadDir = __DIR__ . "/../uploads/admin/";
$uploadUrl = "../uploads/admin/";

if (!empty($admin['photo']) && file_exists($uploadDir . $admin['photo'])) {
    $profile_img = $uploadUrl . $admin['photo'];
} else {
    $profile_img =
        "https://ui-avatars.com/api/?name=" .
        urlencode($admin['first_name']." ".$admin['last_name']) .
        "&background=4D995A&color=fff&size=200";
}
?>

<style>
:root{
    --green:#4D995A;
    --brown:#A9746E;
    --white:#FFFFFF;
}
.sidebar{
    width:260px;
    min-height:100vh;
    background:var(--white);
    border-right:3px solid var(--green);
    position:fixed;
    top:0;
    left:0;
    padding:22px 18px;
}
.sidebar-profile{text-align:center;}
.sidebar-profile img{
    width:110px;height:110px;border-radius:50%;
    border:4px solid var(--green);object-fit:cover;
}
.sidebar a{
    display:block;padding:11px 15px;margin-bottom:8px;
    border-radius:14px;color:var(--brown);
    text-decoration:none;transition:.2s;
}
.sidebar a.active,.sidebar a:hover{
    background:var(--green);color:#fff;font-weight:600;
}
.main-content{margin-left:280px;padding:30px;}
</style>

<div class="sidebar">
    <div class="sidebar-profile mb-3">
        <img src="<?= $profile_img ?>?v=<?= time() ?>" alt="Admin">
        <h6 class="fw-bold mt-2 mb-0">
            <?= htmlspecialchars($admin['first_name']." ".$admin['last_name']) ?>
        </h6>
        <small class="text-muted">Admin</small>
    </div>

    <hr>

    <a href="dashboard.php" class="<?= $current=='dashboard.php'?'active':'' ?>">
        <i class="fa fa-chart-line"></i> Dashboard
    </a>

    <a href="user_management.php" class="<?= $current=='user_management.php'?'active':'' ?>">
        <i class="fa fa-users"></i> User Management
    </a>

    <a href="appointments.php" class="<?= $current=='appointments.php'?'active':'' ?>">
        <i class="fa fa-calendar-check"></i> Appointments
    </a>

    <a href="payments.php" class="<?= $current=='payments.php'?'active':'' ?>">
        <i class="fa fa-credit-card"></i> Payments
    </a>

    <a href="edit_profile.php" class="<?= $current=='edit_profile.php'?'active':'' ?>">
        <i class="fa fa-user-pen"></i> Edit Profile
    </a>

    <a href="../auth/logout.php" onclick="return confirm('Logout?')" class="text-danger">
        <i class="fa fa-right-from-bracket"></i> Logout
    </a>
</div>
