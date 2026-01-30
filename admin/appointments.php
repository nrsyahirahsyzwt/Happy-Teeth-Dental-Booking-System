<?php
include "../config/db.php";
include "../includes/header.php";
include "../includes/admin_sidebar.php";

/* ================= SECURITY ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= UPDATE STATUS ================= */
if (isset($_GET['approve'])) {
    $id = (int) $_GET['approve'];

    // ONLY approve if PAID
    mysqli_query($conn, "
        UPDATE appointments 
        SET status='Confirmed' 
        WHERE appointment_id=$id AND status='Paid'
    ");

    header("Location: appointments.php");
    exit;
}

if (isset($_GET['reject'])) {
    $id = (int) $_GET['reject'];

    mysqli_query($conn, "
        UPDATE appointments 
        SET status='Cancelled' 
        WHERE appointment_id=$id
    ");

    header("Location: appointments.php");
    exit;
}

/* ================= FETCH APPOINTMENTS ================= */
$result = mysqli_query($conn, "
    SELECT
        a.appointment_id,
        u.first_name,
        u.last_name,
        s.service_name,
        b.booking_date,
        b.start_time,
        a.status
    FROM appointments a
    JOIN users u ON a.user_id = u.user_id
    JOIN services s ON a.service_id = s.service_id
    JOIN bookings b ON a.booking_id = b.booking_id
    ORDER BY b.booking_date DESC
");
?>

<div class="main-content">

<h4 class="mb-3">📅 Manage Appointments</h4>

<div class="card shadow p-3">

<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>Patient</th>
    <th>Service</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
    <th width="200">Action</th>
</tr>
</thead>
<tbody>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= htmlspecialchars($row['first_name']." ".$row['last_name']) ?></td>
    <td><?= htmlspecialchars($row['service_name']) ?></td>
    <td><?= date('d M Y', strtotime($row['booking_date'])) ?></td>
    <td><?= date('h:i A', strtotime($row['start_time'])) ?></td>
    <td>
        <span class="badge 
            <?= $row['status']=='Confirmed' ? 'bg-success' :
                ($row['status']=='Paid' ? 'bg-primary' :
                ($row['status']=='Pending' ? 'bg-warning' : 'bg-danger')) ?>">
            <?= $row['status'] ?>
        </span>
    </td>

    <td>
        <?php if ($row['status'] == 'Paid'): ?>
            <a href="?approve=<?= $row['appointment_id'] ?>"
               class="btn btn-success btn-sm">
               Approve
            </a>

            <a href="?reject=<?= $row['appointment_id'] ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Reject this appointment?')">
               Reject
            </a>

        <?php elseif ($row['status'] == 'Pending'): ?>
            <small class="text-muted">Waiting for payment</small>

        <?php else: ?>
            <small class="text-muted">No action</small>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>
</div>

<?php include "../includes/footer.php"; ?>
