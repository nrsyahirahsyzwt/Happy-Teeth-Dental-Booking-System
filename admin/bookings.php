<?php
include "../config/db.php";
include "../includes/header.php";
include "../includes/admin_sidebar.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

/* FILTER */
$date     = $_GET['date'] ?? '';
$dentist  = $_GET['dentist'] ?? '';

$where = [];

if ($date) {
    $where[] = "b.booking_date = '$date'";
}
if ($dentist) {
    $where[] = "d.dentist_id = $dentist";
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

/* APPOINTMENTS */
$appointments = mysqli_query($conn, "
    SELECT
        a.status,
        u.first_name, u.last_name,
        b.booking_date, b.start_time, b.end_time,
        d.fullname AS dentist_name
    FROM appointments a
    JOIN users u ON a.user_id = u.user_id
    JOIN bookings b ON a.booking_id = b.booking_id
    JOIN dentists d ON b.dentist_id = d.dentist_id
    $whereSQL
    ORDER BY b.booking_date DESC
");

/* DENTIST LIST */
$dentists = mysqli_query($conn, "SELECT dentist_id, fullname FROM dentists");
?>

<div class="main-content">

<h4 class="mb-3">📅 Appointments</h4>

<!-- FILTER -->
<div class="card shadow p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">

        <div class="col-md-4">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="<?= $date ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Dentist</label>
            <select name="dentist" class="form-select">
                <option value="">All Dentists</option>
                <?php while ($d = mysqli_fetch_assoc($dentists)): ?>
                    <option value="<?= $d['dentist_id'] ?>"
                        <?= $dentist == $d['dentist_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['fullname']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-success w-100">Filter</button>
        </div>

    </form>
</div>

<!-- TABLE -->
<div class="card shadow p-3">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th>Dentist</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($appointments) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($appointments)): ?>

            <tr>
                <td><?= htmlspecialchars($row['first_name']." ".$row['last_name']) ?></td>
                <td><?= date('d M Y', strtotime($row['booking_date'])) ?></td>
                <td>
                    <?= date('h:i A', strtotime($row['start_time'])) ?> -
                    <?= date('h:i A', strtotime($row['end_time'])) ?>
                </td>
                <td><?= htmlspecialchars($row['dentist_name']) ?></td>
                <td>
                    <span class="badge
                        <?= $row['status']=='Confirmed' ? 'bg-success' :
                            ($row['status']=='Pending' ? 'bg-warning' : 'bg-danger') ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
            </tr>

            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center text-muted">
                    No appointments found
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

</div>

<?php include "../includes/footer.php"; ?>
