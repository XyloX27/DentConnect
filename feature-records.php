<?php
require_once 'includes/config.php';
include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h1 class="display-4" style="color: #9370DB;">Digital Records</h1>
        <p class="lead">Your complete dental history, securely stored and accessible anytime.</p>
        <hr class="my-4" style="border-color: #87CEEB;">
    </div>
</div>

<div class="row align-items-center mb-5">
    <div class="col-md-6 text-center">
        <i class="fas fa-file-medical-alt fa-10x" style="color: #87CEEB;"></i>
    </div>
    <div class="col-md-6">
        <h3 style="color: #87CEEB;">Treatment history</h3>
        <p>See all past treatments, prescriptions, and X‑rays in one place.</p>
        <h3 style="color: #87CEEB;">Secure cloud storage</h3>
        <p>Your data is encrypted and only accessible by you and your dentist.</p>
        <h3 style="color: #87CEEB;">Paperless convenience</h3>
        <p>No more filling out forms at every visit – your info is already there.</p>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="patient/history.php" class="btn btn-primary btn-lg mt-3">View My Records</a>
        <?php else: ?>
            <button onclick="requireLogin()" class="btn btn-primary btn-lg mt-3">View My Records</button>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-file-prescription fa-4x mb-3" style="color: #87CEEB;"></i>
                <h5>Prescriptions</h5>
                <p>Access your current and past prescriptions anytime.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-x-ray fa-4x mb-3" style="color: #87CEEB;"></i>
                <h5>X‑Ray Archive</h5>
                <p>All your uploaded X‑rays stored and organized by date.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-shield-alt fa-4x mb-3" style="color: #87CEEB;"></i>
                <h5>Privacy First</h5>
                <p>We follow strict data protection standards (HIPAA principles).</p>
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