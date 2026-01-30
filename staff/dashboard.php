<?php
session_start();
require "../config/db.php";
require "../includes/header.php";

/* ================= AUTH ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Staff") {
    header("Location: ../auth/login.php");
    exit;
}

$staff_id = (int)$_SESSION['user_id'];
$today = date("Y-m-d");

/* ================= STAFF INFO ================= */
$stmt = $conn->prepare("SELECT first_name FROM users WHERE user_id=? LIMIT 1");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$staff = $stmt->get_result()->fetch_assoc();

/* ================= GREETING ================= */
$hour = date("H");
$greeting = $hour < 12 ? "Good Morning ☀️" : ($hour < 18 ? "Good Afternoon 🌤️" : "Good Evening 🌙");

/* ================= BIG STATS ================= */
$totalBookings = mysqli_fetch_row(
    mysqli_query($conn,"SELECT COUNT(*) FROM bookings")
)[0];

$todayApptsCount = mysqli_fetch_row(
    mysqli_query($conn,"SELECT COUNT(*) FROM bookings WHERE booking_date='$today'")
)[0];

/* ================= TODAY APPOINTMENTS ================= */
$todayAppointments = mysqli_query($conn,"
    SELECT b.start_time, u.first_name, u.last_name, d.fullname AS dentist
    FROM appointments a
    JOIN bookings b ON a.booking_id = b.booking_id
    JOIN users u ON a.user_id = u.user_id
    JOIN dentists d ON b.dentist_id = d.dentist_id
    WHERE b.booking_date='$today'
    ORDER BY b.start_time
");

/* ================= DENTISTS TODAY ================= */
$dentistsToday = mysqli_query($conn,"
    SELECT DISTINCT d.fullname
    FROM bookings b
    JOIN dentists d ON b.dentist_id = d.dentist_id
    WHERE b.booking_date='$today'
");

/* ================= LINE CHART (SAFE 7 DAYS) ================= */
$dates = [];
$counts = [];

/* prepare last 7 days */
for ($i = 6; $i >= 0; $i--) {
    $date = date("Y-m-d", strtotime("-$i days"));
    $dates[] = date("d M", strtotime($date));
    $counts[$date] = 0;
}

/* fetch real data */
$qLine = mysqli_query($conn,"
    SELECT booking_date, COUNT(*) total
    FROM bookings
    WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY booking_date
");

while ($r = mysqli_fetch_assoc($qLine)) {
    $counts[$r['booking_date']] = (int)$r['total'];
}

$counts = array_values($counts);

/* ================= STATUS CHART (AUTO MATCH DB) ================= */
$statusLabels = [];
$statusCounts = [];

$qStatus = mysqli_query($conn,"
    SELECT status, COUNT(*) total
    FROM appointments
    GROUP BY status
");

while ($s = mysqli_fetch_assoc($qStatus)) {
    $statusLabels[] = $s['status'];
    $statusCounts[] = (int)$s['total'];
}

if (empty($statusLabels)) {
    $statusLabels = ['No Data'];
    $statusCounts = [0];
}
?>

<?php include "../includes/staff_sidebar.php"; ?>

<style>
:root{--green:#4D995A;--brown:#A9746E;--cream:#FFF1C1}
body{background:var(--cream)}
.hero{background:linear-gradient(135deg,var(--green),var(--brown));color:#fff;padding:28px;border-radius:24px}
.cardx{background:#fff;padding:22px;border-radius:20px}
</style>

<div class="container-fluid px-4 main-content">

<!-- HEADER -->
<div class="hero mb-4">
    <h4>Staff Dashboard</h4>
    <small>
        <?= $greeting ?>,
        <?= htmlspecialchars($staff['first_name']) ?> ·
        <?= date("l, d M Y") ?>
    </small>
</div>

<!-- STATS -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="cardx text-center">
            <h6>Total Bookings</h6>
            <h1><?= $totalBookings ?></h1>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cardx text-center">
            <h6>Today Appointments</h6>
            <h1><?= $todayApptsCount ?></h1>
        </div>
    </div>
</div>

<!-- CHARTS -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="cardx">
            <h6>📈 Bookings (Last 7 Days)</h6>
            <canvas id="lineChart" height="120"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="cardx">
            <h6>🟢🟡🔴 Appointment Status</h6>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<!-- TODAY APPOINTMENTS -->
<div class="card shadow p-4 mb-4 rounded-4">
    <h6>📅 Today’s Appointments</h6>
    <table class="table table-sm mt-3">
        <thead class="table-light">
            <tr>
                <th>Time</th>
                <th>Patient</th>
                <th>Dentist</th>
            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($todayAppointments)==0): ?>
            <tr>
                <td colspan="3" class="text-center text-muted">
                    No appointments 🎉
                </td>
            </tr>
        <?php endif; ?>

        <?php while($t=mysqli_fetch_assoc($todayAppointments)): ?>
            <tr>
                <td><?= date("h:i A",strtotime($t['start_time'])) ?></td>
                <td><?= htmlspecialchars($t['first_name']." ".$t['last_name']) ?></td>
                <td><?= htmlspecialchars($t['dentist']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- DENTISTS TODAY -->
<div class="card shadow p-4 mb-4 rounded-4">
    <h6>🦷 Dentists on Schedule Today</h6>
    <table class="table table-sm mt-3">
        <tbody>
        <?php while($d=mysqli_fetch_assoc($dentistsToday)): ?>
            <tr>
                <td><?= htmlspecialchars($d['fullname']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const lineChart = new Chart(
    document.getElementById("lineChart"),
{
    type:"line",
    data:{
        labels: <?= json_encode($dates) ?>,
        datasets:[{
            label:"Bookings",
            data: <?= json_encode($counts) ?>,
            borderColor:"#4D995A",
            backgroundColor:"rgba(77,153,90,.2)",
            fill:true,
            tension:.4
        }]
    },
    options:{
        responsive:true,
        scales:{ y:{ beginAtZero:true } }
    }
});

const statusChart = new Chart(
    document.getElementById("statusChart"),
{
    type:"doughnut",
    data:{
        labels: <?= json_encode($statusLabels) ?>,
        datasets:[{
            data: <?= json_encode($statusCounts) ?>,
            backgroundColor:[
                "#28a745","#ffc107","#dc3545",
                "#0d6efd","#6f42c1","#20c997"
            ]
        }]
    },
    options:{ responsive:true }
});

/* auto refresh every 30s */
setInterval(()=>location.reload(),30000);
</script>

<?php include "../includes/footer.php"; ?>
