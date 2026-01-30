<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Happy Teeth Dental Booking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
:root{
    --pink:#C95E82;
    --green-dark:#4D995A;
    --green-light:#9BDA7C;
    --beige:#F4E0DC;
    --white:#FFFFFF;
}

/* GLOBAL */
body{
    background: var(--beige);
    font-family: 'Segoe UI', sans-serif;
}

.card{
    border:none;
    border-radius:16px;
}

/* BUTTONS */
.btn-primary{
    background: var(--pink);
    border:none;
}
.btn-primary:hover{
    background:#b44e6e;
}

.btn-success{
    background: var(--green-dark);
    border:none;
}
.btn-success:hover{
    background:#3d7f4b;
}

/* BOOKING SLOTS */
.available{
    background: var(--green-light);
    color:#000;
    padding:15px;
    border-radius:12px;
}
.booked{
    background: var(--pink);
    color:#fff;
    padding:15px;
    border-radius:12px;
}
.slot{
    margin-bottom:15px;
}

/* PAGE TITLE */
.page-title{
    color: var(--green-dark);
    font-weight:700;
}
</style>
</head>

<body>

<div class="container mt-4">
