<?php
session_start();
require_once "../config/db.php";

/* AUTH */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Staff") {
    header("Location: ../auth/login.php");
    exit;
}

include "../includes/header.php";
include "../includes/staff_sidebar.php";

/* FETCH PATIENTS */
$patients = mysqli_query($conn, "
    SELECT
        user_id,
        first_name,
        last_name,
        phone_number,
        email,
        created_at,
        photo
    FROM users
    WHERE role = 'Patient'
    ORDER BY first_name ASC
");
?>

<style>
.patient-card{
    border-radius:18px;
    padding:16px;
    background:#fff;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
    transition:.2s;
}
.patient-card:hover{ transform:translateY(-2px); }

.patient-avatar{
    width:48px;
    height:48px;
    border-radius:50%;
    background:#4D995A;
    color:#fff;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

.patient-avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.badge-walkin{ background:#ffc107; color:#000; }
.search-box{ max-width:420px; }
</style>

<div class="main-content">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>👥 Patients</h3>
    <input type="text"
           id="search"
           class="form-control search-box"
           placeholder="Search name or phone...">
</div>

<div class="row g-3" id="patientList">

<?php if (mysqli_num_rows($patients) === 0): ?>
    <div class="col-12 text-muted text-center">
        No patients found
    </div>
<?php endif; ?>

<?php while ($p = mysqli_fetch_assoc($patients)):

    $initials = strtoupper(
        substr($p['first_name'],0,1) .
        substr($p['last_name'],0,1)
    );

    $isWalkIn = empty($p['email']);
?>

<div class="col-md-6 patient-item">
    <div class="patient-card d-flex align-items-center gap-3">

        <!-- AVATAR -->
        <div class="patient-avatar">
            <?php if (!empty($p['photo']) && file_exists("../uploads/patients/".$p['photo'])): ?>
                <img src="../uploads/patients/<?= htmlspecialchars($p['photo']) ?>" alt="Patient">
            <?php else: ?>
                <?= $initials ?>
            <?php endif; ?>
        </div>

        <!-- INFO -->
        <div class="flex-grow-1">
            <div class="fw-bold">
                <?= htmlspecialchars($p['first_name']." ".$p['last_name']) ?>
            </div>

            <div class="text-muted small">
                📞 <?= htmlspecialchars($p['phone_number'] ?: '—') ?>
            </div>

            <div class="small mt-1">
                <?php if ($isWalkIn): ?>
                    <span class="badge badge-walkin">Walk‑in</span>
                <?php else: ?>
                    <span class="badge bg-success">Registered</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- DATE -->
        <div class="text-muted small text-end">
            <?= date("d M Y", strtotime($p['created_at'])) ?>
        </div>

    </div>
</div>

<?php endwhile; ?>

</div>
</div>

<script>
document.getElementById("search").addEventListener("keyup", function(){
    let keyword = this.value.toLowerCase();
    document.querySelectorAll(".patient-item").forEach(card => {
        card.style.display =
            card.innerText.toLowerCase().includes(keyword)
            ? "block"
            : "none";
    });
});
</script>

<?php include "../includes/footer.php"; ?>
