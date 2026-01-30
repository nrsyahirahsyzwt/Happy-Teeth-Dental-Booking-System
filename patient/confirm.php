<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

/* AUTH */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

/* VALIDATE INPUT */
if (!isset($_GET['id'], $_GET['service_id'])) {
    header("Location: booking.php");
    exit;
}

$booking_id = (int) $_GET['id'];
$service_id = (int) $_GET['service_id'];
$user_id    = $_SESSION['user_id'];

/* FETCH SLOT */
$slot = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT b.*, d.fullname
    FROM bookings b
    JOIN dentists d ON b.dentist_id = d.dentist_id
    WHERE b.booking_id = $booking_id
"));

if (!$slot || $slot['is_booked'] == 1) {
    header("Location: booking.php");
    exit;
}

/* FETCH SERVICE */
$service = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM services WHERE service_id = $service_id
"));

if (!$service) {
    header("Location: booking.php");
    exit;
}

/* CONFIRM */
if (isset($_POST['confirm'])) {

    mysqli_query($conn, "
        INSERT INTO appointments (user_id, booking_id, service_id, status)
        VALUES ($user_id, $booking_id, $service_id, 'Pending Payment')
    ");

    $appointment_id = mysqli_insert_id($conn);

    mysqli_query($conn, "
        UPDATE bookings SET is_booked = 1
        WHERE booking_id = $booking_id
    ");

    header("Location: payment.php?appointment_id=$appointment_id");
    exit;
}
?>

<?php include "../includes/patient_sidebar.php"; ?>

<style>
:root{
    --brown:#8B5E59;
    --cream:#FFF4D6;
    --green:#2F9E44;
    --green-dark:#238636;
    --red:#E63946;
    --red-dark:#C1121F;
}

body{
    background:linear-gradient(135deg,#FFF1C1,#FFE0D6);
}

.confirm-card{
    border-radius:30px;
    padding:45px;
    background:rgba(255,255,255,0.75);
    backdrop-filter:blur(12px);
    box-shadow:0 25px 60px rgba(0,0,0,.15);
}

.confirm-title{
    font-weight:900;
    color:var(--brown);
}

.info-row{
    background:#FFF9E9;
    border-radius:18px;
    padding:20px;
    margin-bottom:15px;
}

.info-row span{
    font-weight:800;
}

.price{
    font-size:1.3rem;
    font-weight:900;
    color:var(--green);
}

.btn-confirm{
    background:linear-gradient(135deg,var(--green),var(--green-dark));
    color:white;
    border:none;
    border-radius:16px;
    padding:14px;
    font-weight:800;
    transition:.2s;
}

.btn-confirm:hover{
    transform:translateY(-2px);
}

.btn-cancel{
    background:linear-gradient(135deg,var(--red),var(--red-dark));
    color:white;
    border:none;
    border-radius:16px;
    padding:13px;
    font-weight:700;
}
</style>

<div class="main-content">
<div class="container my-5">
<div class="row justify-content-center">
<div class="col-lg-6 col-md-8">

<div class="card confirm-card text-center">

    <h3 class="confirm-title mb-4">
        <i class="fa fa-calendar-check"></i><br>
        Confirm Appointment
    </h3>

    <div class="info-row">
        <span>Dentist</span><br>
        <?= $slot['fullname'] ?>
    </div>

    <div class="info-row">
        <span>Service</span><br>
        <?= $service['service_name'] ?>
        <div class="price mt-1">
            RM <?= number_format($service['price'],2) ?>
        </div>
    </div>

    <div class="info-row">
        <span>Date</span><br>
        <?= date("d F Y", strtotime($slot['booking_date'])) ?>
    </div>

    <div class="info-row">
        <span>Time</span><br>
        <?= date("h:i A", strtotime($slot['start_time'])) ?> –
        <?= date("h:i A", strtotime($slot['end_time'])) ?>
    </div>

    <form method="post" class="mt-4">
        <button name="confirm" class="btn btn-confirm w-100 mb-3">
            <i class="fa fa-check"></i> Confirm & Proceed to Payment
        </button>

        <a href="booking.php"
           class="btn btn-cancel w-100"
           onclick="return confirm('Cancel and release this time slot?')">
           Cancel
        </a>
    </form>

</div>

</div>
</div>
</div>
</div>

<?php include "../includes/footer.php"; ?>
