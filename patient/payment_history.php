<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$payments = mysqli_query($conn, "
    SELECT p.*
    FROM payments p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    WHERE a.user_id = $user_id
    ORDER BY p.payment_date DESC
");
?>

<style>
:root{
    --pink:#FFB7B2;
    --brown:#A9746E;
    --cream:#FFF1C1;
    --green:#4D995A;
}

body{ background:var(--cream); }

.page-title{
    font-weight:900;
    background:linear-gradient(90deg,var(--green),var(--brown));
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.card{ border-radius:25px; }

.payment-card{
    background:#FFF7DD;
    border-radius:22px;
    padding:20px 24px;
    transition:.3s;
}
.payment-card:hover{
    transform:translateY(-5px);
    box-shadow:0 15px 40px rgba(0,0,0,.15);
}

.badge-paid{ background:var(--green); }

.amount{
    font-size:1.4rem;
    font-weight:800;
    color:var(--brown);
}

.empty-box{
    text-align:center;
    padding:40px;
    color:#777;
}
</style>

<?php include "../includes/patient_sidebar.php"; ?>

<div class="main-content">

    <h3 class="page-title mb-4">💳 Payment History</h3>

    <div class="card shadow p-4">

        <?php if (mysqli_num_rows($payments) === 0) { ?>
            <div class="empty-box">
                <i class="fa fa-receipt fa-3x mb-3 text-muted"></i>
                <p class="mb-0">No payment records found</p>
            </div>
        <?php } ?>

        <?php while ($p = mysqli_fetch_assoc($payments)) { ?>

            <div class="payment-card mb-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="amount">
                        RM <?= number_format($p['amount'], 2) ?>
                    </span>
                    <span class="badge badge-paid">
                        <i class="fa fa-circle-check"></i> Paid
                    </span>
                </div>

                <div class="small text-muted">
                    <div>
                        <i class="fa fa-credit-card"></i>
                        Payment Method:
                        <b><?= htmlspecialchars($p['payment_method']) ?></b>
                    </div>

                    <div>
                        <i class="fa fa-calendar"></i>
                        Payment Date:
                        <?= !empty($p['payment_date'])
                            ? date("d M Y, h:i A", strtotime($p['payment_date']))
                            : "-" ?>
                    </div>
                </div>

            </div>

        <?php } ?>

    </div>
</div>

<?php include "../includes/footer.php"; ?>
