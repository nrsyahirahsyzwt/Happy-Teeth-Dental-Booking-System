<?php
session_start();
require "../config/db.php";
include "../includes/header.php";
include "../includes/admin_sidebar.php";

/* ================= SECURITY ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= FETCH PAYMENTS ================= */
$payments = mysqli_query($conn, "
    SELECT
        p.payment_id,
        p.amount,
        p.payment_method,
        p.bank,
        p.payment_status,
        p.payment_date,
        u.first_name,
        u.last_name
    FROM payments p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    JOIN users u ON a.user_id = u.user_id
    ORDER BY p.payment_date DESC
");
?>

<style>
body{ background:#FFF1C1; }
.card{ border-radius:22px; }

.status-badge{
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}
.status-paid{ background:#d4edda;color:#155724; }
.status-pending{ background:#fff3cd;color:#856404; }
.status-failed{ background:#f8d7da;color:#721c24; }
</style>

<div class="main-content px-4">

<h4 class="mb-3">💳 Patient Payments</h4>

<div class="card shadow p-3">

<table class="table table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>#</th>
    <th>Patient</th>
    <th>Amount (RM)</th>
    <th>Method</th>
    <th>Bank</th>
    <th>Status</th>
    <th>Date</th>
</tr>
</thead>

<tbody>
<?php if (mysqli_num_rows($payments) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($payments)): ?>
<tr>
    <td><?= $row['payment_id'] ?></td>

    <td>
        <?= htmlspecialchars($row['first_name']." ".$row['last_name']) ?>
    </td>

    <td>
        RM <?= number_format($row['amount'], 2) ?>
    </td>

    <td><?= htmlspecialchars($row['payment_method']) ?></td>

    <td><?= htmlspecialchars($row['bank'] ?? '-') ?></td>

    <td>
        <span class="status-badge
            <?= $row['payment_status']=='Paid' ? 'status-paid' :
               ($row['payment_status']=='Pending' ? 'status-pending' : 'status-failed') ?>">
            <?= $row['payment_status'] ?>
        </span>
    </td>

    <td>
        <?= date("d M Y", strtotime($row['payment_date'])) ?>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="7" class="text-center text-muted">
        No payments found
    </td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>

<?php include "../includes/footer.php"; ?>
