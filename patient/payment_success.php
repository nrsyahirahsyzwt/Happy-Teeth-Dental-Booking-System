<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

/* ================= SECURITY ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

$appointment_id = intval($_GET['appointment_id'] ?? 0);
$status = $_GET['status'] ?? '';

if ($appointment_id <= 0 || $status !== 'success') {
    header("Location: dashboard.php");
    exit;
}

/* ================= FETCH APPOINTMENT + PAYMENT ================= */
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        a.appointment_id,
        a.status,
        s.service_name,
        s.price,
        d.fullname AS dentist_name,
        b.booking_date,
        b.start_time,
        b.end_time,
        p.payment_method,
        p.bank,
        p.payment_date
    FROM appointments a
    JOIN bookings b ON a.booking_id = b.booking_id
    JOIN dentists d ON b.dentist_id = d.dentist_id
    JOIN services s ON a.service_id = s.service_id
    LEFT JOIN payments p ON p.appointment_id = a.appointment_id
    WHERE a.appointment_id = $appointment_id
"));

if (!$data) {
    echo "<div class='container my-5 alert alert-danger'>Invalid payment record.</div>";
    include "../includes/footer.php";
    exit;
}
?>

<style>
body{
    background:#FFF9F2;
}

.success-card{
    border-radius:26px;
    padding:40px;
}

.icon-success{
    font-size:60px;
    color:#5B8C5A;
}
</style>

<div class="container my-5">
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card shadow success-card text-center">

    <div class="mb-3">
        <i class="fa fa-circle-check icon-success"></i>
    </div>

    <h2 class="fw-bold text-success mb-2">
        Payment Successful
    </h2>

    <p class="text-muted mb-4">
        Thank you for your payment. Your appointment request has been received
        and will be reviewed by our clinic staff.
    </p>

    <hr class="my-4">

    <div class="text-start">
        <p><b>Dentist:</b> <?= htmlspecialchars($data['dentist_name']) ?></p>
        <p><b>Service:</b> <?= htmlspecialchars($data['service_name']) ?></p>
        <p><b>Date:</b> <?= date("d M Y", strtotime($data['booking_date'])) ?></p>
        <p><b>Time:</b>
            <?= date("h:i A", strtotime($data['start_time'])) ?> -
            <?= date("h:i A", strtotime($data['end_time'])) ?>
        </p>
        <p><b>Payment Method:</b>
            <?= htmlspecialchars($data['payment_method']) ?>
            <?= $data['bank'] ? "({$data['bank']})" : "" ?>
        </p>
        <p><b>Amount Paid:</b>
            <span class="fw-bold text-success">
                RM <?= number_format($data['price'],2) ?>
            </span>
        </p>
        <p><b>Status:</b>
            <span class="badge bg-warning text-dark">
                Pending Approval
            </span>
        </p>
    </div>

    <div class="alert alert-info mt-4 text-start">
        <i class="fa fa-info-circle me-1"></i>
        Your appointment is <b>not confirmed yet</b>.
        Our staff will review and approve your request shortly.
        You will be notified once it is confirmed.
    </div>

    <div class="d-flex justify-content-center gap-3 mt-4">
        <a href="dashboard.php" class="btn btn-success btn-lg">
            <i class="fa fa-home"></i> Back to Dashboard
        </a>
        <a href="appointments.php" class="btn btn-outline-secondary btn-lg">
            View Appointments
        </a>
    </div>

</div>

</div>
</div>
</div>

<?php include "../includes/footer.php"; ?>
