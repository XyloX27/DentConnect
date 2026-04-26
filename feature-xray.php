<?php
require_once 'includes/config.php';
include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h1 class="display-4" style="color: #9370DB;">AI‑Powered X‑Ray Analysis</h1>
        <p class="lead">Upload your dental X‑ray and let our artificial intelligence help detect issues early.</p>
        <hr class="my-4" style="border-color: #87CEEB;">
    </div>
</div>

<div class="row align-items-center mb-5">
    <div class="col-md-6 order-md-2 text-center">
        <i class="fas fa-x-ray fa-10x" style="color: #87CEEB;"></i>
    </div>
    <div class="col-md-6 order-md-1">
        <h3 style="color: #87CEEB;">Instant insights</h3>
        <p>Within seconds, our AI highlights potential cavities, bone loss, or impacted teeth.</p>
        <h3 style="color: #87CEEB;">Confidence scores</h3>
        <p>Each finding comes with a percentage so you understand the certainty.</p>
        <h3 style="color: #87CEEB;">Easy sharing</h3>
        <p>Share results directly with your dentist before your appointment.</p>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="patient/xray.php" class="btn btn-primary btn-lg mt-3">Try AI Analysis</a>
        <?php else: ?>
            <button onclick="requireLogin()" class="btn btn-primary btn-lg mt-3">Try AI Analysis</button>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-brain fa-4x mb-3" style="color: #87CEEB;"></i>
                <h5>Machine Learning</h5>
                <p>Trained on thousands of dental images for accurate detection.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-chart-line fa-4x mb-3" style="color: #87CEEB;"></i>
                <h5>Detailed Reports</h5>
                <p>Get a comprehensive PDF report of your analysis (coming soon).</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="fas fa-user-md fa-4x mb-3" style="color: #87CEEB;"></i>
                <h5>Doctor Reviewed</h5>
                <p>Your dentist can review the AI findings during consultation.</p>
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