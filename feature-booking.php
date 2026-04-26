<?php
require_once 'includes/config.php';
include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h1 class="display-4" style="color: #9370DB;">Smart Appointment Booking</h1>
        <p class="lead">Never wait in line again – book your dental visit with just a few clicks.</p>
        <hr class="my-4" style="border-color: #87CEEB;">
    </div>
</div>

<div class="row align-items-center mb-5">
    <div class="col-md-6 text-center">
        <i class="fas fa-calendar-alt fa-10x" style="color: #87CEEB;"></i>
    </div>
    <div class="col-md-6">
        <h3 style="color: #87CEEB;">Real‑time availability</h3>
        <p>See exactly which time slots are free and book instantly. No more phone tag.</p>
        <h3 style="color: #87CEEB;">Choose your dentist</h3>
        <p>Select from our team of experienced dentists based on your preference.</p>
        <h3 style="color: #87CEEB;">Automated reminders</h3>
        <p>Get email and SMS reminders before your appointment – reduce no‑shows.</p>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="patient/book.php" class="btn btn-primary btn-lg mt-3">Book Now</a>
        <?php else: ?>
            <button onclick="requireLogin()" class="btn btn-primary btn-lg mt-3">Book Now</button>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-calendar-check fa-4x mb-3" style="color: #87CEEB;"></i>
                <h5>24/7 Booking</h5>
                <p>Available anytime, anywhere – on your phone, tablet, or computer.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-bell fa-4x mb-3" style="color: #87CEEB;"></i>
                <h5>Smart Reminders</h5>
                <p>Never forget an appointment with reminders 24h and 1h before.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-sync-alt fa-4x mb-3" style="color: #87CEEB;"></i>
                <h5>Easy Rescheduling</h5>
                <p>Change or cancel appointments online without a phone call.</p>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="index.php" class="btn btn-secondary">← Back to Home</a>
</div>

<script>
function requireLogin() {
    alert("You need to Register/Login first.");
    window.location.href = "patient/login.php";
}
</script>

<?php include 'includes/footer.php'; ?>