<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="Patient"){
    header("Location: ../auth/login.php");
    exit;
}

$appointment_id = intval($_GET['appointment_id'] ?? 0);

$app = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT
        a.appointment_id,
        d.fullname,
        b.booking_date,
        b.start_time,
        b.end_time,
        s.service_name,
        s.price AS service_fee
    FROM appointments a
    JOIN bookings b ON a.booking_id=b.booking_id
    JOIN dentists d ON b.dentist_id=d.dentist_id
    JOIN services s ON a.service_id=s.service_id
    WHERE a.appointment_id=$appointment_id
"));

if(!$app){
    echo "<div class='container my-5 alert alert-danger'>Appointment not found</div>";
    include "../includes/footer.php";
    exit;
}
?>

<style>
:root{
    --pink:#FFB7B2;
    --brown:#A9746E;
    --cream:#FFF1C1;
    --green:#4D995A;
}

body{
    background:var(--cream);
}

/* CARD */
.pay-card{
    border-radius:26px;
    padding:35px;
}

/* SUMMARY */
.summary-box{
    background:#FFF7DD;
    border-radius:18px;
    padding:20px;
}

/* PAYMENT OPTION */
.pay-option{
    border:2px solid #ddd;
    border-radius:16px;
    padding:14px 18px;
    cursor:pointer;
    transition:.3s;
}
.pay-option:hover{
    border-color:var(--green);
    background:#F8FFF9;
}

/* BUTTON */
.btn-pay{
    background:var(--green);
    border:none;
    font-weight:600;
    border-radius:14px;
}
.btn-pay:hover{
    background:#3e7f4a;
}
</style>

<div class="container my-5">
<div class="row justify-content-center">
<div class="col-md-7">

<div class="card shadow pay-card">

    <h3 class="fw-bold text-center mb-4 text-success">
        <i class="fa fa-credit-card"></i> Payment
    </h3>

    <!-- SUMMARY -->
    <div class="summary-box mb-4">
        <p><b>Dentist:</b> <?= $app['fullname'] ?></p>
        <p><b>Date:</b> <?= date("d M Y",strtotime($app['booking_date'])) ?></p>
        <p><b>Time:</b>
            <?= date("h:i A",strtotime($app['start_time'])) ?> -
            <?= date("h:i A",strtotime($app['end_time'])) ?>
        </p>
        <p>
            <b>Service:</b>
            <span class="badge bg-success"><?= $app['service_name'] ?></span>
        </p>
    </div>

    <h4 class="text-center mb-4">
        Total:
        <span class="text-success fw-bold">
            RM <?= number_format($app['service_fee'],2) ?>
        </span>
    </h4>

    <form method="post" action="payment_progress.php">
        <input type="hidden" name="appointment_id" value="<?= $appointment_id ?>">
        <input type="hidden" name="amount" value="<?= $app['service_fee'] ?>">

        <label class="fw-bold mb-2">Payment Method</label>

        <label class="pay-option mb-2 w-100">
            <input type="radio" name="method" value="Cash" required>
            Cash
        </label>

        <label class="pay-option mb-2 w-100">
            <input type="radio" name="method" value="Online Banking" id="onlineBanking">
            Online Banking
        </label>

        <div id="bankDropdown" class="mt-2 ms-3" style="display:none;">
            <select name="bank" class="form-select rounded-3">
                <option value="">-- Select Bank --</option>
                <option value="Maybank">Maybank</option>
                <option value="CIMB Clicks">CIMB Clicks</option>
                <option value="Bank Islam">Bank Islam</option>
                <option value="RHB Now">RHB Now</option>
                <option value="Public Bank">Public Bank</option>
                <option value="Hong Leong Connect">Hong Leong Bank</option>
            </select>
        </div>

        <label class="pay-option mt-2 w-100">
            <input type="radio" name="method" value="Debit / Credit Card">
            Debit / Credit Card
        </label>

        <div class="d-grid gap-2 mt-4">
            <button name="pay" class="btn btn-pay btn-lg">
                <i class="fa fa-lock"></i> Pay Now
            </button>
            <a href="dashboard.php" class="btn btn-outline-secondary rounded-3">
                Pay Later
            </a>
                    <button name="cancel" class="btn btn-pay btn-lg">
                <i class="fa fa-lock"></i> Cancel
            </button>
        </div>
    </form>

</div>
</div>
</div>
</div>

<script>
const onlineRadio = document.getElementById("onlineBanking");
const bankDropdown = document.getElementById("bankDropdown");

document.querySelectorAll('input[name="method"]').forEach(r=>{
    r.addEventListener("change",()=>{
        bankDropdown.style.display = onlineRadio.checked ? "block" : "none";
    });
});
</script>

<?php include "../includes/footer.php"; ?>
