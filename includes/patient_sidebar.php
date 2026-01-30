<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$current = basename($_SERVER['PHP_SELF']);

$user = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT first_name, last_name, photo FROM users WHERE user_id = $user_id LIMIT 1"
));

if (!empty($user['photo']) && file_exists("../uploads/patients/" . $user['photo'])) {
    $profile_img = "../uploads/patients/" . $user['photo'];
} else {
    $profile_img =
        "https://ui-avatars.com/api/?name=" .
        urlencode($user['first_name'] . " " . $user['last_name']) .
        "&background=A9746E&color=fff&size=200";
}
?>

<style>
:root{
    --pink:#FFB7B2;
    --brown:#A9746E;
    --cream:#FFF1C1;
    --green:#4D995A;
    --white:#FFFFFF;
}

/* SIDEBAR */
.sidebar{
    width:260px;
    min-height:100vh;
    background:var(--white);
    border-right:3px solid var(--pink);
    position:fixed;
    top:0;
    left:0;
    padding:22px 18px;
}

/* PROFILE */
.sidebar-profile{
    text-align:center;
}

.sidebar-profile img{
    width:110px;
    height:110px;
    object-fit:cover;
    border-radius:50%;
    border:4px solid var(--green);
}

/* LINKS */
.sidebar a{
    display:block;
    padding:11px 15px;
    border-radius:14px;
    color:var(--brown);
    text-decoration:none;
    margin-bottom:8px;
    transition:.2s;
    text-align:left; /* FORCE LEFT */
}

.sidebar a i{
    margin-right:8px;
}

/* ACTIVE */
.sidebar a.active,
.sidebar a:hover{
    background:var(--pink);
    color:#fff;
    font-weight:600;
}

/* MAIN CONTENT */
.main-content{
    margin-left:280px;
    padding:30px;
}
</style>

<div class="sidebar">

    <!-- PROFILE (CENTER ONLY) -->
    <div class="sidebar-profile mb-3">
        <img src="<?= $profile_img ?>" alt="Profile Picture" class="mb-2">

        <h6 class="fw-bold mb-0" style="color:var(--brown)">
            <?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?>
        </h6>
        <small class="text-muted">Patient</small>
    </div>

    <hr>

    <!-- MENU (LEFT) -->
    <a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">
        <i class="fa fa-house"></i> Dashboard
    </a>

    <a href="booking.php" class="<?= $current === 'booking.php' ? 'active' : '' ?>">
        <i class="fa fa-calendar-plus"></i> Book Appointment
    </a>

<a href="my_appointments.php" class="<?= $current === 'my_appointments.php' ? 'active' : '' ?>">
    <i class="fa fa-calendar-check"></i> My Appointments
</a>

    <a href="payment_history.php" class="<?= $current === 'payment_history.php' ? 'active' : '' ?>">
        <i class="fa fa-wallet"></i> Payment History
    </a>

    <a href="edit_profile.php" class="<?= $current === 'edit_profile.php' ? 'active' : '' ?>">
        <i class="fa fa-user-pen"></i> Edit Profile
    </a>

    <a href="../auth/logout.php"
       onclick="return confirm('Logout?')"
       class="text-danger">
        <i class="fa fa-right-from-bracket"></i> Logout
    </a>

</div>
