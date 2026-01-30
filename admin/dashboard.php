<?php
include "../config/db.php";
include "../includes/header.php";
include "../includes/admin_sidebar.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= SUMMARY ================= */
$totalUsers = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM users"
))[0];

$totalPatients = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM users WHERE role='Patient'"
))[0];

$totalStaff = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM users WHERE role='Staff'"
))[0];

$totalAppointments = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM appointments"
))[0];

$totalServices = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM services"
))[0];

$todayRevenue = mysqli_fetch_row(mysqli_query($conn,
    "SELECT IFNULL(SUM(amount),0) FROM payments WHERE DATE(payment_date)=CURDATE()"
))[0];

$latestAppointments = mysqli_query($conn, "
    SELECT u.first_name, u.last_name, b.booking_date, a.status
    FROM appointments a
    JOIN users u ON a.user_id = u.user_id
    JOIN bookings b ON a.booking_id = b.booking_id
    ORDER BY b.booking_date DESC
    LIMIT 5
");
?>

<style>
.hero{
    background:linear-gradient(135deg,#4D995A,#A9746E);
    color:#fff;
    padding:26px;
    border-radius:22px;
    margin-bottom:20px;
}
.card{ border-radius:20px; }
</style>

<div class="main-content">

<!-- HERO -->
<div class="hero">
    <h4 class="mb-1">
        👋 Hi, Admin <?= htmlspecialchars($admin['first_name']." ".$admin['last_name']) ?>
    </h4>
    <small>Welcome back, Admin 🦷</small>
</div>

<!-- MAIN STATS -->
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card shadow p-3 text-center">
            <h6>Total Users</h6>
            <h3 class="text-success"><?= $totalUsers ?></h3>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow p-3 text-center">
            <h6>Total Appointments</h6>
            <h3 class="text-primary"><?= $totalAppointments ?></h3>
        </div>
    </div>
</div>

<!-- QUICK STATS -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small>Patients</small>
            <h4><?= $totalPatients ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small>Staff</small>
            <h4><?= $totalStaff ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small>Total Services Available</small>
            <h4><?= $totalServices ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small>Today Revenue</small>
            <h4>RM <?= number_format($todayRevenue,2) ?></h4>
        </div>
    </div>
</div>

<!-- LATEST APPOINTMENTS -->
<div class="card shadow p-3 mb-4">
    <h6 class="fw-bold mb-2">📅 Latest Appointments</h6>

    <table class="table table-sm mb-0">
        <tr>
            <th>Patient</th>
            <th>Date</th>
            <th>Status</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($latestAppointments)): ?>
        <tr>
            <td><?= htmlspecialchars($row['first_name']." ".$row['last_name']) ?></td>
            <td><?= $row['booking_date'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</div>

<?php include "../includes/footer.php"; ?>
