<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

$appointment_id = (int) ($_GET['appointment_id'] ?? 0);

$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        a.appointment_id,
        a.status,
        d.fullname,
        b.booking_date,
        b.start_time,
        b.end_time,
        s.service_name,
        p.amount,
        p.payment_method,
        p.bank,
        p.payment_date
    FROM appointments a
    JOIN bookings b ON a.booking_id = b.booking_id
    JOIN dentists d ON b.dentist_id = d.dentist_id
    JOIN services s ON a.service_id = s.service_id
    JOIN payments p ON a.appointment_id = p.appointment_id
    WHERE a.appointment_id = $appointment_id
"));


if (!$data) {
    echo "<div class='container my-5 alert alert-danger'>Receipt not found</div>";
    include "../includes/footer.php";
    exit;
}
?>

<style>
.receipt-card{
    max-width:600px;
    margin:auto;
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 20px 50px rgba(0,0,0,.15);
}
.receipt-title{
    font-weight:900;
    color:#2F9E44;
}
</style>

<div class="container my-5">
<div class="receipt-card">

    <h3 class="receipt-title text-center mb-4">
        🧾 Payment Receipt
    </h3>

    <p><b>Receipt No:</b> #<?= $data['appointment_id'] ?></p>
    <p><b>Date:</b> <?= date("d M Y, h:i A", strtotime($data['payment_date'])) ?></p>

    <hr>

    <p><b>Dentist:</b> <?= $data['fullname'] ?></p>
    <p><b>Service:</b> <?= $data['service_name'] ?></p>
    <p><b>Appointment:</b>
        <?= date("d M Y", strtotime($data['booking_date'])) ?>,
        <?= date("h:i A", strtotime($data['start_time'])) ?> –
        <?= date("h:i A", strtotime($data['end_time'])) ?>
    </p>

    <hr>

<p><b>Payment Method:</b> <?= $data['payment_method'] ?></p>
    <?php if ($data['bank']) : ?>
        <p><b>Bank:</b> <?= $data['bank'] ?></p>
    <?php endif; ?>

    <h4 class="text-success fw-bold mt-3">
        Total Paid: RM <?= number_format($data['amount'],2) ?>
    </h4>

    <p class="badge bg-success mt-2">PAID</p>

<div class="d-grid gap-2 mt-4">
    <button onclick="window.print()" class="btn btn-outline-success">
        🖨 Print Receipt
    </button>

    <a href="dashboard.php" class="btn btn-success">
        ⬅ Back to Dashboard
    </a>
</div>

</div>
</div>

<?php include "../includes/footer.php"; ?>
