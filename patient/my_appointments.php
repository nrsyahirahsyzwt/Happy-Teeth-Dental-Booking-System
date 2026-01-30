<?php
session_start();
require_once "../config/db.php";
include "../includes/header.php";

/* AUTH */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* FETCH APPOINTMENTS */
$appointments = mysqli_query($conn, "
    SELECT 
        a.appointment_id,
        a.status,
        b.booking_date,
        b.start_time,
        b.end_time,
        d.fullname AS dentist_name,
        s.service_name
    FROM appointments a
    JOIN bookings b ON a.booking_id = b.booking_id
    JOIN dentists d ON b.dentist_id = d.dentist_id
    JOIN services s ON a.service_id = s.service_id
    WHERE a.user_id = $user_id
    ORDER BY b.booking_date DESC, b.start_time DESC
");
?>

<style>
:root{
    --green:#4D995A;
    --cream:#FFF1C1;
    --brown:#A9746E;
}
body{ background:var(--cream); }

.page-title{
    font-weight:800;
    color:var(--brown);
}

.card{
    border-radius:22px;
}

.badge-status{
    padding:6px 12px;
    border-radius:12px;
    font-size:.85rem;
}

.status-Confirmed{ background:#198754; color:#fff; }
.status-Pending{ background:#ffc107; color:#000; }
.status-Cancelled{ background:#dc3545; color:#fff; }

.table thead{
    background:#fff;
}
</style>

<?php include "../includes/patient_sidebar.php"; ?>

<div class="main-content p-4">

    <h3 class="page-title mb-4">
        <i class="fa fa-calendar-check"></i> My Appointments
    </h3>

    <div class="card shadow p-4">

        <?php if(mysqli_num_rows($appointments) == 0){ ?>
            <p class="text-muted text-center mb-0">
                No appointments found 😌
            </p>
        <?php }else{ ?>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Dentist</th>
                        <th>Service</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                <?php while($a = mysqli_fetch_assoc($appointments)){ ?>

                    <tr>
                        <td>
                            <?= date("d M Y", strtotime($a['booking_date'])) ?>
                        </td>
                        <td>
                            <?= date("h:i A", strtotime($a['start_time'])) ?>
                            –
                            <?= date("h:i A", strtotime($a['end_time'])) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($a['dentist_name']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($a['service_name']) ?>
                        </td>
                        <td>
                            <span class="badge-status status-<?= $a['status'] ?>">
                                <?= $a['status'] ?>
                            </span>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
        </div>

        <?php } ?>

    </div>

</div>

<?php include "../includes/footer.php"; ?>
