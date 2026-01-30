<?php
session_start();
require_once "../config/db.php"; // <<< PENTING (untuk sidebar)
include "../includes/header.php";

/* ================= SECURITY ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= USER NAME ================= */
$first = $_SESSION['first_name'] ?? 'Patient';
$last  = $_SESSION['last_name']  ?? '';
$name  = trim($first . ' ' . $last);

include "../includes/patient_sidebar.php";
?>

<style>
:root{
    --green:#4D995A;
    --cream:#FFF1C1;
}
body{ background:var(--cream); }

.help-card{
    background:#fff;
    border-radius:22px;
    padding:24px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.help-btn{
    display:block;
    background:#F8F9FA;
    border-radius:18px;
    padding:18px;
    text-decoration:none;
    color:#000;
    transition:.2s;
}
.help-btn:hover{
    background:#E9F5EE;
    transform:translateY(-2px);
}
</style>

<div class="main-content p-4">

<div class="help-card text-center mb-4">
    <h4 class="fw-bold">
        <i class="fa-solid fa-headset text-success"></i>
        User Support
    </h4>
    <p class="text-muted mb-0">
        Click an issue — we'll be happy to help
    </p>
</div>

<div class="row g-3">

<!-- Appointment -->
<div class="col-md-6">
<a class="help-btn"
href="mailto:support@dentalclinic.com
?subject=Appointment Issue - <?= urlencode($name) ?>
&body=Hi, my name is <?= urlencode($name) ?>.%0D%0A%0D%0AI have an issue regarding my appointment.">
🦷 <b>Appointment Issue</b><br>
<small class="text-muted">Booking / Reschedule / Cancel</small>
</a>
</div>

<!-- Payment -->
<div class="col-md-6">
<a class="help-btn"
href="mailto:support@dentalclinic.com
?subject=Payment & Billing - <?= urlencode($name) ?>
&body=Hi, my name is <?= urlencode($name) ?>.%0D%0A%0D%0AI have a question about payment or billing.">
💳 <b>Payment & Billing</b><br>
<small class="text-muted">Charges & payments</small>
</a>
</div>

<!-- Account -->
<div class="col-md-6">
<a class="help-btn"
href="mailto:support@dentalclinic.com
?subject=Account Support - <?= urlencode($name) ?>
&body=Hi, my name is <?= urlencode($name) ?>.%0D%0A%0D%0AI need help with my account.">
👤 <b>Account Support</b><br>
<small class="text-muted">Login / profile</small>
</a>
</div>

<!-- General -->
<div class="col-md-6">
<a class="help-btn"
href="mailto:support@dentalclinic.com
?subject=General Enquiry - <?= urlencode($name) ?>
&body=Hi, my name is <?= urlencode($name) ?>.%0D%0A%0D%0AI would like to ask a general question.">
❓ <b>General Enquiry</b><br>
<small class="text-muted">Clinic & services</small>
</a>
</div>

</div>
</div>

<?php include "../includes/footer.php"; ?>
