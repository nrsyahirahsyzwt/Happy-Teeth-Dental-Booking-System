<?php
session_start();

/* ===============================
   AUTH
================================ */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Staff") {
    header("Location: ../auth/login.php");
    exit;
}

include "../includes/header.php";
include "../includes/staff_sidebar.php";

/* ===============================
   WEEKLY SCHEDULE (STATIC)
================================ */
$schedule = [
    "Monday"    => ["Dr. Sarah Lee", "Dr. Tan Mei"],
    "Tuesday"   => ["Dr. Tan Mei", "Dr. Hafiz Ali"],
    "Wednesday" => ["Dr. Hafiz Ali", "Dr. Lim Wei"],
    "Thursday"  => ["Dr. Lim Wei", "Dr. Nina Zainal"],
    "Friday"    => ["Dr. Nina Zainal", "Dr. Sarah Lee"],
];
?>

<style>
:root{
    --green:#4D995A;
    --soft:#E9F5EE;
}

/* GRID */
.week-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap:18px;
}

/* DAY CARD */
.day-card{
    background:#fff;
    border-radius:18px;
    padding:18px;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
    border-top:6px solid var(--green);
    position:relative;
    overflow:hidden;
    animation:fadeUp .5s ease;
}

/* subtle shine */
.day-card::after{
    content:"";
    position:absolute;
    top:0; left:-100%;
    width:100%; height:100%;
    background:linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,.4),
        transparent
    );
    animation:shine 6s infinite;
}

/* TITLE */
.day-title{
    font-weight:700;
    font-size:16px;
    margin-bottom:14px;
    color:var(--green);
    text-align:center;
}

/* DENTIST PILL */
.dentist-pill{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    padding:10px 14px;
    margin-bottom:8px;
    border-radius:30px;
    background:var(--soft);
    color:#1E6B42;
    font-weight:600;
    font-size:14px;
    transition:.25s;
}

/* tooth icon */
.dentist-pill::before{
    content:"🦷";
    animation:float 2.5s ease-in-out infinite;
}

/* hover (soft only) */
.dentist-pill:hover{
    background:#dff0e6;
    transform:translateY(-1px);
}

/* FOOT NOTE */
.note{
    margin-top:22px;
    font-size:13px;
    color:#6c757d;
    text-align:center;
}

/* ANIMATIONS */
@keyframes float{
    0%,100%{ transform:translateY(0); }
    50%{ transform:translateY(-3px); }
}

@keyframes fadeUp{
    from{ opacity:0; transform:translateY(8px); }
    to{ opacity:1; transform:none; }
}

@keyframes shine{
    0%{ left:-100%; }
    40%,100%{ left:120%; }
}
</style>

<div class="main-content">
    <h3 class="mb-4">🦷 Dentists Weekly Schedule</h3>

    <div class="week-grid">
        <?php foreach ($schedule as $day => $dentists): ?>
            <div class="day-card">
                <div class="day-title"><?= htmlspecialchars($day) ?></div>

                <?php foreach ($dentists as $dentist): ?>
                    <span class="dentist-pill">
                        <?= htmlspecialchars($dentist) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="note">
        📌 This schedule repeats every week (Monday – Friday)<br>
        Any changes will be informed accordingly.
    </div>
</div>

<?php include "../includes/footer.php"; ?>
