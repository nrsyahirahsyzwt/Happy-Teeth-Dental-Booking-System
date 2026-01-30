<?php
session_start();
require_once "../config/db.php";
include "../includes/header.php";

/* ================= AUTH ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= DATE (AUTO SKIP WEEKEND) ================= */
$selected_date = $_GET['date'] ?? date("Y-m-d");
$date = new DateTime($selected_date);

while (in_array($date->format('N'), [6,7])) {
    $date->modify('+1 day');
}
$selected_date = $date->format("Y-m-d");

$prev = clone $date;
do { $prev->modify('-1 day'); } while (in_array($prev->format('N'), [6,7]));
$prev_date = $prev->format("Y-m-d");

$next = clone $date;
do { $next->modify('+1 day'); } while (in_array($next->format('N'), [6,7]));
$next_date = $next->format("Y-m-d");

/* ================= DATA ================= */
$services = mysqli_query($conn,"SELECT * FROM services ORDER BY service_name");

/* ================= DENTISTS ================= */
$dentists_all = mysqli_query($conn,"
    SELECT dentist_id, fullname, photo
    FROM dentists
    ORDER BY dentist_id
");

$dentists = [];
while($d = mysqli_fetch_assoc($dentists_all)){
    $dentists[] = $d;
}

/* ================= ROTATION ================= */
$totalDentists = count($dentists);
$dayIndex = (int)date('z', strtotime($selected_date));
$startIndex = $dayIndex % $totalDentists;

$todayDentists = [];
for($i=0; $i<2; $i++){
    $todayDentists[] = $dentists[($startIndex + $i) % $totalDentists];
}

/* ================= AUTO SLOT ================= */
foreach($todayDentists as $d){

    $check = mysqli_query($conn,"
        SELECT 1 FROM bookings
        WHERE dentist_id={$d['dentist_id']}
        AND booking_date='$selected_date'
        LIMIT 1
    ");

    if(mysqli_num_rows($check) == 0){

        $slots = [
            "09:00:00","10:00:00","11:00:00",
            "12:00:00","14:00:00","15:00:00","16:00:00"
        ];

        foreach($slots as $start){
            $end = date("H:i:s", strtotime($start."+1 hour"));

            mysqli_query($conn,"
                INSERT INTO bookings
                (dentist_id, booking_date, start_time, end_time, is_booked)
                VALUES
                ({$d['dentist_id']}, '$selected_date', '$start', '$end', 0)
            ");
        }
    }
}
?>

<?php include "../includes/patient_sidebar.php"; ?>

<style>
:root{
    --green:#4D995A;
    --cream:#FFF1C1;
}
body{ background:var(--cream); }
.card{ border-radius:22px; }

.date-nav{
    background:#fff;
    padding:18px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.dentist-card{ border-left:6px solid var(--green); }

.dentist-header{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:15px;
}
.dentist-header img{
    width:64px;
    height:64px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid var(--green);
}

.slot-btn{
    border-radius:14px;
    font-weight:600;
    padding:12px;
}
.slot-btn.available{ background:var(--green); color:#fff; }
.slot-btn.booked{ background:#dc3545; color:#fff; opacity:.6; }
</style>

<div class="main-content p-4">

<h3 class="fw-bold mb-4">📅 Book Appointment</h3>

<div class="date-nav text-center mb-4">
    <a href="?date=<?= $prev_date ?>" class="btn btn-outline-secondary btn-sm float-start">‹ Previous</a>
    <b><?= date("l, d F Y", strtotime($selected_date)) ?></b>
    <a href="?date=<?= $next_date ?>" class="btn btn-outline-secondary btn-sm float-end">Next ›</a>
</div>

<?php foreach($todayDentists as $d):

    if(!empty($d['photo']) && file_exists("../uploads/dentists/".$d['photo'])){
        $dentist_img = "../uploads/dentists/".$d['photo'];
    }else{
        $dentist_img = "https://ui-avatars.com/api/?name="
            .urlencode($d['fullname'])
            ."&background=4D995A&color=fff&size=200";
    }
?>

<div class="card shadow p-4 mb-4 dentist-card">
    <div class="dentist-header">
        <img src="<?= $dentist_img ?>">
        <h5 class="fw-bold text-success mb-0">
            <?= htmlspecialchars($d['fullname']) ?>
        </h5>
    </div>

    <div class="row g-3">
    <?php
    $slots = mysqli_query($conn,"
        SELECT * FROM bookings
        WHERE dentist_id={$d['dentist_id']}
        AND booking_date='$selected_date'
        ORDER BY start_time
    ");

    while($b=mysqli_fetch_assoc($slots)):
    ?>
        <div class="col-lg-2 col-md-3 col-4">

        <?php if(!$b['is_booked']): ?>
            <form method="get" action="confirm.php">
                <input type="hidden" name="id" value="<?= $b['booking_id'] ?>">

                <select name="service_id" class="form-select mb-1" required>
                    <option value="">Service</option>
                    <?php
                    mysqli_data_seek($services,0);
                    while($s=mysqli_fetch_assoc($services)):
                    ?>
                        <option value="<?= $s['service_id'] ?>">
                            <?= htmlspecialchars($s['service_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button class="btn slot-btn available w-100">
                    <?= date("h:i A",strtotime($b['start_time'])) ?>
                </button>
            </form>
        <?php else: ?>
            <div class="btn slot-btn booked w-100">
                <?= date("h:i A",strtotime($b['start_time'])) ?>
            </div>
        <?php endif; ?>

        </div>
    <?php endwhile; ?>
    </div>
</div>

<?php endforeach; ?>

</div>

<?php include "../includes/footer.php"; ?>
