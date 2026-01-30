<?php
session_start();
require "../config/db.php";

/* AUTH */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Staff") {
    header("Location: ../auth/login.php");
    exit;
}

/* FETCH APPOINTMENTS */
$q = mysqli_query($conn,"
    SELECT
        a.appointment_id,
        a.status,
        b.booking_date,
        b.start_time,
        u.first_name,
        u.last_name,
        d.fullname AS dentist
    FROM appointments a
    JOIN bookings b ON a.booking_id = b.booking_id
    JOIN users u ON a.user_id = u.user_id
    JOIN dentists d ON b.dentist_id = d.dentist_id
    ORDER BY b.booking_date DESC, b.start_time ASC
");

/* COUNT STATUS */
/* COUNT STATUS (SAFE) */
$counts = [
    'confirmed' => 0,
    'pending' => 0,
    'cancelled' => 0
];

mysqli_data_seek($q, 0);

while ($c = mysqli_fetch_assoc($q)) {
    if (!empty($c['status'])) {
        $status = strtolower(trim($c['status']));

        if (isset($counts[$status])) {
            $counts[$status]++;
        }
    }
}

mysqli_data_seek($q, 0);

mysqli_data_seek($q,0);

include "../includes/header.php";
include "../includes/staff_sidebar.php";
?>

<style>
body{background:#FFF1C1}
.card{border-radius:22px}

/* STATUS BADGES */
.status-badge{
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    display:inline-block;
}
.status-badge.confirmed{background:#d4edda;color:#155724}
.status-badge.pending{background:#fff3cd;color:#856404}
.status-badge.cancelled{background:#f8d7da;color:#721c24}
</style>

<div class="container-fluid px-4 main-content">

<div class="card shadow p-4">

<!-- HEADER + FILTER -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold m-0">📋 Manage Appointments</h4>

    <div class="d-flex gap-2">
        <input type="text" id="searchPatient"
               class="form-control"
               placeholder="Search patient...">

        <select id="statusFilter" class="form-select">
            <option value="all">All Status</option>
            <option value="confirmed">Confirmed</option>
            <option value="pending">Pending</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
</div>

<!-- STATUS COUNT -->
<div class="row mb-3">
    <div class="col"><div class="alert alert-success">Confirmed: <?= $counts['confirmed'] ?></div></div>
    <div class="col"><div class="alert alert-warning">Pending: <?= $counts['pending'] ?></div></div>
    <div class="col"><div class="alert alert-danger">Cancelled: <?= $counts['cancelled'] ?></div></div>
</div>

<!-- TABLE -->
<div class="table-responsive">
<table class="table align-middle">
<thead class="table-light">
<tr>
    <th>#</th>
    <th>Patient</th>
    <th>Dentist</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<?php if(mysqli_num_rows($q)==0): ?>
<tr><td colspan="6" class="text-center text-muted">No appointments 😴</td></tr>
<?php endif; ?>

<?php while($r=mysqli_fetch_assoc($q)):
$cls=strtolower($r['status']);
?>
<tr data-status="<?= $cls ?>">
    <td><?= $r['appointment_id'] ?></td>
    <td><?= htmlspecialchars($r['first_name']." ".$r['last_name']) ?></td>
    <td><?= htmlspecialchars($r['dentist']) ?></td>
    <td><?= date("d M Y",strtotime($r['booking_date'])) ?></td>
    <td><?= date("h:i A",strtotime($r['start_time'])) ?></td>
    <td>
        <span class="status-badge <?= $cls ?>"
              data-id="<?= $r['appointment_id'] ?>"
              data-status="<?= $r['status'] ?>">
            <?= $r['status'] ?>
        </span>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</div>
</div>

<script>
/* FILTER */
const statusFilter=document.getElementById("statusFilter");
const searchInput=document.getElementById("searchPatient");

function applyFilters(){
    const s=statusFilter.value;
    const k=searchInput.value.toLowerCase();
    document.querySelectorAll("tbody tr").forEach(r=>{
        const ok=(s==="all"||r.dataset.status===s)
               && r.innerText.toLowerCase().includes(k);
        r.style.display=ok?"":"none";
    });
}
statusFilter.onchange=applyFilters;
searchInput.onkeyup=applyFilters;

/* CLICK BADGE TO CHANGE STATUS */
document.querySelectorAll(".status-badge").forEach(badge=>{
    badge.onclick=()=>{
        const current=badge.dataset.status;
        const next = current==="Confirmed" ? "Pending"
                   : current==="Pending" ? "Cancelled"
                   : "Confirmed";

        fetch("update_status.php",{
            method:"POST",
            headers:{ "Content-Type":"application/x-www-form-urlencoded" },
            body:"id="+badge.dataset.id+"&status="+next
        }).then(()=>location.reload());
    };
});
</script>

<?php include "../includes/footer.php"; ?>
