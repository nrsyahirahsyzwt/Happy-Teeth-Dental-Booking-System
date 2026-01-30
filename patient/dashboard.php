<?php
include "../config/db.php";
include "../includes/header.php";

/* ================= SECURITY ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* ================= USER ================= */
$user = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT first_name, last_name FROM users WHERE user_id = $user_id"
));

/* ================= STATS ================= */
$totalApp = mysqli_fetch_row(mysqli_query(
    $conn,
    "SELECT COUNT(*) FROM appointments WHERE user_id = $user_id"
))[0];

$upcomingCount = mysqli_fetch_row(mysqli_query(
    $conn,
    "SELECT COUNT(*) FROM appointments
     WHERE user_id = $user_id AND status = 'Confirmed'"
))[0];

/* ================= NEXT APPOINTMENT ================= */
$next = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        b.booking_date, b.start_time, b.end_time,
        d.fullname AS dentist_name,
        s.service_name, a.status
    FROM appointments a
    JOIN bookings b ON a.booking_id = b.booking_id
    JOIN dentists d ON b.dentist_id = d.dentist_id
    JOIN services s ON a.service_id = s.service_id
    WHERE a.user_id = $user_id
    ORDER BY b.booking_date, b.start_time
    LIMIT 1
"));

/* ================= COUNTDOWN ================= */
$countdownText = "No upcoming appointment 🎉";
if ($next) {
    $diff = strtotime($next['booking_date'].' '.$next['start_time']) - time();
    if ($diff > 0) {
        $d = floor($diff / 86400);
        $h = floor(($diff % 86400) / 3600);
        $m = floor(($diff % 3600) / 60);
        $countdownText = "⏳ In <b>$d</b>d <b>$h</b>h <b>$m</b>m";
    }
}

/* ================= PROGRESS ================= */
$completed = $upcomingCount;
$target = 10;
$progress = min(100, ($completed / $target) * 100);

/* ================= MESSAGE ================= */
$messages = [
    "Remember to floss daily 🪥",
    "Keep smiling! 😁",
    "Avoid sugary drinks 🦷",
    "We look forward to seeing you soon 😉"
];
$randomMsg = $messages[array_rand($messages)];
?>

<style>
:root{
    --pink:#FFB7B2;
    --brown:#A9746E;
    --cream:#FFF1C1;
    --green:#4D995A;
}
body{ background:var(--cream); }
.card{ border-radius:22px; }

.hero{
    background:linear-gradient(135deg,var(--green),var(--brown));
    color:#fff;
    padding:28px;
    border-radius:26px;
    margin-bottom:18px;
}

.stat-card{
    background:#fff;
    padding:22px;
    text-align:center;
    border-radius:22px;
}

.appointment-box{
    background:#FFF7DD;
    border-radius:20px;
    padding:18px;
}

/* FLOATING SUPPORT BUTTON */
.floating-support{
    position:fixed;
    bottom:24px;
    right:24px;
    z-index:999;
}
.floating-support a{
    background:#4D995A;
    color:#fff;
    width:60px;
    height:60px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 8px 20px rgba(0,0,0,.25);
    font-size:22px;
}
.floating-support a:hover{
    background:#3d7f4b;
    color:#fff;
}
</style>

<?php include "../includes/patient_sidebar.php"; ?>

<div class="main-content">

<!-- HERO -->
<div class="hero">
    <h4>👋 Hi, <?= htmlspecialchars($user['first_name']." ".$user['last_name']) ?></h4>
    <small>Welcome back to your dental dashboard 🦷</small>
</div>

<!-- STATS -->
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="stat-card">
            <p>Total Appointments</p>
            <h3 class="fw-bold text-danger"><?= $totalApp ?></h3>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <p>Upcoming</p>
            <h3 class="fw-bold text-success"><?= $upcomingCount ?></h3>
        </div>
    </div>
</div>

<!-- NEXT APPOINTMENT -->
<div class="card shadow p-3 mb-3">
    <h6 class="fw-bold">📅 Next Appointment</h6>

    <?php if ($next): ?>
        <div class="appointment-box">
            <p><b>Dentist:</b> <?= htmlspecialchars($next['dentist_name']) ?></p>
            <p><b>Service:</b> <?= htmlspecialchars($next['service_name']) ?></p>
            <p><b>Date:</b> <?= date('d M Y', strtotime($next['booking_date'])) ?></p>
            <p><b>Time:</b>
                <?= date('h:i A', strtotime($next['start_time'])) ?> -
                <?= date('h:i A', strtotime($next['end_time'])) ?>
            </p>
            <span class="badge bg-success"><?= $next['status'] ?></span>
            <div class="small mt-2"><?= $countdownText ?></div>
        </div>
    <?php else: ?>
        <p class="text-muted">No upcoming appointment 🎉</p>
    <?php endif; ?>
</div>

<!-- JOURNEY + MESSAGE -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow p-3">
            <h6>🦷 Dental Journey</h6>
            <div class="progress mt-2">
                <div class="progress-bar bg-success" style="width:<?= $progress ?>%">
                    <?= round($progress) ?>%
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow p-3">
            <h6>💬 Message</h6>
            <div class="bg-light p-3 rounded">
                <?= $randomMsg ?>
            </div>
        </div>
    </div>
</div>

</div>

<!-- FLOATING SUPPORT BUTTON -->
<div class="floating-support">
    <a href="desk_help.php" title="User Support">
        <i class="fa-solid fa-headset"></i>
    </a>
</div>

<?php include "../includes/footer.php"; ?>
