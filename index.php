<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Happy Teeth Dental Clinic</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
:root{
    --pink: #FFB7B2;       /* Strawberry */
    --brown: #A9746E;      /* Chocolate */
    --cream: #FFF1C1;      /* Vanilla */
    --green-accent: #4D995A; /* Optional accent */
    --white: #FFFFFF;
}

body{
    font-family:'Segoe UI', sans-serif;
    background: var(--cream);
    color: #333;
}

/* NAVBAR */
.navbar{
    background: var(--white);
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}
.navbar-brand{
    font-weight:900;
    color: var(--brown)!important;
}

/* HERO */
.hero{
    min-height:100vh;
    padding-top:90px;
    background:linear-gradient(135deg,var(--pink),var(--brown));
    display:flex;
    align-items:center;
    color:white;
}
.hero-card{
    background: var(--white);
    padding:50px;
    border-radius:25px;
    box-shadow:0 15px 40px rgba(0,0,0,0.25);
    animation: float 4s ease-in-out infinite;
}

@keyframes float{
    0%,100%{transform:translateY(0);}
    50%{transform:translateY(-8px);}
}

.hero-image{
    background-image:url('https://images.unsplash.com/photo-1588776814546-1ffcf47267a5');
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    min-height:100vh;
}

@media(max-width:768px){
    .hero-image{
        min-height:300px;
    }
}

/* SECTION */
.section{
    padding:80px 0;
}
.section-title{
    font-weight:800;
    color: var(--brown);
}

/* CARD */
.card-custom{
    border:none;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* CTA */
.cta{
    background:
    linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),
    url('https://images.unsplash.com/photo-1606813909358-8e4aab297927');
    background-size:cover;
    background-position:center;
    color:white;
    padding:80px 0;
}
input[type="email"]{
    border-radius:12px 0 0 12px;
}
.input-group .btn{
    border-radius:0 12px 12px 0;
}
input[type="email"]:focus{
    box-shadow:none;
    border-color: var(--green-accent);
}

/* FOOTER */
footer{
    background:#222;
    color:white;
    padding:30px 0;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="https://pin.it/4c7ZlZcyW">🦷 Happy Teeth Dental Clinic</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item"><a class="btn btn-success ms-2" href="auth/register.php">Register</a></li>
                <li class="nav-item"><a class="btn btn-success ms-2" href="auth/login.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero p-0">
    <div class="container-fluid">
        <div class="row g-0 min-vh-100">

            <!-- LEFT CONTENT -->
            <div class="col-md-6 d-flex align-items-center justify-content-center"
                 style="background:linear-gradient(135deg,var(--pink),var(--green-dark));"
                 data-aos="fade-right">
                <div class="hero-card text-center text-dark">
                    <img src="assets/images/cliniclogo.png" width="150" class="mb-3" alt="Clinic Logo">
                    <h1 class="fw-bold">Happy Teeth Dental Clinic</h1>
                    <p class="text-muted">
                        Professional dental care made easy.<br>
                        Book appointments, manage payments & smile confidently.
                    </p>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <a href="auth/register.php" class="btn btn-success btn-lg">Get Started</a>
                        <a href="#services" class="btn btn-outline-success btn-lg">Our Services</a>
                    </div>
                    <div class="d-flex justify-content-center gap-4 mt-4 text-muted small">
                        <span>🦷 Certified Dentists</span>
                        <span>⏰ Flexible Hours</span>
                        <span>💳 Secure Payment</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT IMAGE -->
            <div class="col-md-6 hero-image" data-aos="fade-left"></div>

        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="section bg-white">
    <div class="container">
        <h2 class="text-center section-title mb-5">Why Choose Us</h2>
        <div class="row text-center">
            <div class="col-md-3" data-aos="fade-up"><i class="fa fa-user-doctor fa-3x mb-3 text-success"></i><h6>Expert Dentists</h6></div>
            <div class="col-md-3" data-aos="fade-up"><i class="fa fa-tooth fa-3x mb-3 text-success"></i><h6>Modern Equipment</h6></div>
            <div class="col-md-3" data-aos="fade-up"><i class="fa fa-wallet fa-3x mb-3 text-success"></i><h6>Affordable Price</h6></div>
            <div class="col-md-3" data-aos="fade-up"><i class="fa fa-face-smile fa-3x mb-3 text-success"></i><h6>Friendly Service</h6></div>
        </div>
    </div>
</section>

<!-- CLINIC HOURS -->
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-custom shadow-sm text-center p-4">
                    <h4 class="fw-bold text-success mb-4">
                        <i class="fa fa-clock me-2"></i>Clinic Opening Hours
                    </h4>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="fw-semibold">🗓 Monday – Thursday</span>
                        <span>9:00 AM – 5:00 PM</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="fw-semibold">🗓 Friday</span>
                        <span>9:00 AM – 1:00 PM / 3.00 PM - 5.00 PM</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 text-danger">
                        <span class="fw-semibold">❌ Saturday, Sunday & Public Holiday</span>
                        <span>Closed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section id="services" class="section">
    <div class="container">
        <h2 class="text-center section-title mb-5">Our Services</h2>

        <div class="row g-4 text-center">

            <div class="col-md-3" data-aos="flip-left">
                <div class="card card-custom p-4 service-card">
                    <img src="assets/images/teethcleaning.png" class="service-img" alt="Teeth Cleaning">
                    <h6 class="fw-bold">Teeth Cleaning</h6>
                </div>
            </div>

            <div class="col-md-3" data-aos="flip-left">
                <div class="card card-custom p-4 service-card">
                    <img src="assets/images/braces.png" class="service-img" alt="Braces">
                    <h6 class="fw-bold">Braces</h6>
                </div>
            </div>

            <div class="col-md-3" data-aos="flip-left">
                <div class="card card-custom p-4 service-card">
                    <img src="assets/images/teethwhitening.png" class="service-img" alt="Teeth Whitening">
                    <h6 class="fw-bold">Teeth Whitening</h6>
                </div>
            </div>

            <div class="col-md-3" data-aos="flip-left">
                <div class="card card-custom p-4 service-card">
                    <img src="assets/images/toothextraction.png" class="service-img" alt="Tooth Extraction">
                    <h6 class="fw-bold">Tooth Extraction</h6>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- MEET OUR DENTISTS -->
<section class="section bg-white">
    <div class="container">
        <h2 class="text-center section-title mb-5">Meet Our Dentists</h2>
        <div class="row g-4 text-center">

            <div class="col-md-4" data-aos="fade-up">
                <div class="card card-custom p-4">
                    <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2" class="rounded-circle mb-3" width="120">
                    <h6>Dr. Aisyah</h6>
                    <small>Orthodontist</small>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up">
                <div class="card card-custom p-4">
                    <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2" class="rounded-circle mb-3" width="120">
                    <h6>Dr. Nina Zainal</h6>
                    <small>Dental Surgeon</small>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up">
                <div class="card card-custom p-4">
                    <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2" class="rounded-circle mb-3" width="120">
                    <h6>Dr. Sofia</h6>
                    <small>General Dentist</small>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="section">
    <div class="container">
        <h2 class="text-center section-title mb-5">Testimonials</h2>
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card card-custom p-4 text-center">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" class="rounded-circle mb-3" width="70">
                    ⭐⭐⭐⭐⭐<br>Friendly staff!
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom p-4 text-center">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" class="rounded-circle mb-3" width="70">
                    ⭐⭐⭐⭐⭐<br>Easy booking system
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom p-4 text-center">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" class="rounded-circle mb-3" width="70">
                    ⭐⭐⭐⭐⭐<br>Highly recommended
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta text-center">
    <div class="container">
        <h2>Ready to Smile?</h2>
        <p>Book your appointment today</p>
        <a href="auth/register.php" class="btn btn-light btn-lg">Register Now</a>
    </div>
</section>

<!-- NEWSLETTER -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4 text-center" style="border-radius:20px;">
                
                <h4 class="fw-bold text-success mb-2">🦷 Join Our Newsletter</h4>
                <p class="text-muted small mb-4">
                    Get dental tips, promotions & clinic updates directly to your email.
                </p>

                <form method="post" action="newsletter.php">
                    <div class="input-group mb-3">
                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="Enter your email address"
                               required>
                        <button class="btn btn-success" type="submit">Subscribe</button>
                    </div>
                </form>

                <small class="text-muted">We respect your privacy. No spam.</small>
            </div>
        </div>
    </div>
</div>

<!-- CONTACT -->
<section id="contact" class="section bg-white text-center">
    <h2 class="section-title mb-3">Contact Us</h2>
    <p>📍 Kuala Lumpur | 📞 012‑3456789 | ✉ happyteethdental@gmail.com</p>
</section>

<?php include "includes/footer.php"; ?>
</body>
</html>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({duration:1000,once:true});
</script>

</body>
</html>
