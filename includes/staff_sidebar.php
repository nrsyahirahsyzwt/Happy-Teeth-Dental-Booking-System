<?php
/* =====================================
   SESSION & DATABASE
===================================== */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/db.php";

/* =====================================
   AUTHORIZATION CHECK
===================================== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Staff") {
    header("Location: ../auth/login.php");
    exit;
}

/* =====================================
   BASIC SETUP
===================================== */
$staff_id    = (int) $_SESSION['user_id'];
$currentPage = basename($_SERVER['PHP_SELF']);

/* =====================================
   FETCH STAFF DATA
===================================== */
$stmt = mysqli_prepare(
    $conn,
    "SELECT first_name, last_name, photo
     FROM users
     WHERE user_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "i", $staff_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$staff  = mysqli_fetch_assoc($result);

/* =====================================
   PROFILE IMAGE HANDLING
===================================== */
$uploadPath = "../uploads/staff/";

$defaultImg = "https://ui-avatars.com/api/?name=" .
    urlencode($staff['first_name'] . " " . $staff['last_name']) .
    "&background=4D995A&color=fff&size=200";

$profileImg = (
    !empty($staff['photo']) &&
    file_exists($uploadPath . $staff['photo'])
)
    ? $uploadPath . $staff['photo']
    : $defaultImg;
?>

<style>
:root {
    --green: #4D995A;
    --brown: #A9746E;
    --white: #FFFFFF;
}

/* SIDEBAR */
.sidebar {
    width: 260px;
    min-height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    padding: 22px 18px;
    background: var(--white);
    border-right: 3px solid var(--green);
}

/* PROFILE */
.sidebar-profile {
    text-align: center;
    margin-bottom: 16px;
}

.sidebar-profile img {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid var(--green);
}

/* LINKS */
.sidebar a {
    display: block;
    padding: 11px 15px;
    margin-bottom: 8px;
    border-radius: 14px;
    color: var(--brown);
    text-decoration: none;
    transition: 0.2s;
}

.sidebar a i {
    margin-right: 8px;
}

.sidebar a.active,
.sidebar a:hover {
    background: var(--green);
    color: #fff;
    font-weight: 600;
}

/* MAIN CONTENT OFFSET */
.main-content {
    margin-left: 280px;
    padding: 30px;
}
</style>

<div class="sidebar">

    <!-- PROFILE -->
    <div class="sidebar-profile">
        <img src="<?= htmlspecialchars($profileImg) ?>" alt="Staff Profile">
        <h6 class="fw-bold mb-0" style="color: var(--brown);">
            <?= htmlspecialchars($staff['first_name'] . " " . $staff['last_name']) ?>
        </h6>
        <small class="text-muted">Staff</small>
    </div>

    <hr>

    <!-- MENU -->
    <a href="dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
        <i class="fa fa-house"></i> Dashboard
    </a>

    <a href="manage_appointments.php" class="<?= $currentPage === 'manage_appointments.php' ? 'active' : '' ?>">
        <i class="fa fa-users"></i> Manage Appointments
    </a>

    <a href="patients.php" class="<?= $currentPage === 'patients.php' ? 'active' : '' ?>">
        <i class="fa fa-users"></i> Patients List
    </a>

    <a href="dentists_schedule.php" class="<?= $currentPage === 'dentists_schedule.php' ? 'active' : '' ?>">
        <i class="fa fa-calendar-days"></i> Dentists Schedule
    </a>

    <a href="edit_profile.php" class="<?= $currentPage === 'edit_profile.php' ? 'active' : '' ?>">
        <i class="fa fa-user-pen"></i> Edit Profile
    </a>

    <a href="../auth/logout.php"
       class="text-danger"
       onclick="return confirm('Logout?');">
        <i class="fa fa-right-from-bracket"></i> Logout
    </a>

</div>
