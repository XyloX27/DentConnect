<?php
require_once 'includes/config.php';
include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="row align-items-center min-vh-80 py-5">
    <div class="col-md-6">
        <h1 class="display-4 fw-bold" style="color: #9370DB;">Your Smile,<br>Our Priority</h1>
        <p class="lead">Experience modern dental care with AI-powered diagnostics and seamless appointment management.</p>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <!-- Original buttons: Get Started (blue) and Login (gray) -->
            <a href="register.php" class="btn btn-primary btn-lg me-2">Get Started</a>
            <a href="patient/login.php" class="btn btn-secondary btn-lg">Login</a>
        <?php else: ?>
            <?php
            // Redirect to appropriate dashboard based on role
            $dashboard = '';
            switch($_SESSION['role']) {
                case 'patient': $dashboard = 'patient/dashboard.php'; break;
                case 'doctor': $dashboard = 'doctor/dashboard.php'; break;
                case 'staff': $dashboard = 'staff/dashboard.php'; break;
                case 'admin': $dashboard = 'admin/dashboard.php'; break;
                default: $dashboard = 'index.php';
            }
            ?>
            <a href="<?php echo $dashboard; ?>" class="btn btn-primary btn-lg">Go to Dashboard</a>
        <?php endif; ?>
    </div>
    <div class="col-md-6 text-center">
        <i class="fas fa-tooth" style="font-size: 200px; color: #87CEEB;"></i>
    </div>
</div>

<!-- Features Section (Now Clickable) -->
<div class="row my-5">
    <h2 class="text-center mb-4" style="color: #9370DB;">Why Choose DentConnect?</h2>
    
    <div class="col-md-4 mb-3">
        <a href="feature-booking.php" class="text-decoration-none">
            <div class="card p-3 text-center h-100">
                <i class="fas fa-calendar-check fa-3x mb-3" style="color: #87CEEB;"></i>
                <h5>Smart Appointment Booking</h5>
                <p>Book, reschedule, or cancel appointments with real-time availability.</p>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 mb-3">
        <a href="feature-xray.php" class="text-decoration-none">
            <div class="card p-3 text-center h-100">
                <i class="fas fa-x-ray fa-3x mb-3" style="color: #87CEEB;"></i>
                <h5>AI X-Ray Analysis</h5>
                <p>Upload dental X-rays and get instant AI-powered insights.</p>
            </div>
        </a>
    </div>
    
    <div class="col-md-4 mb-3">
        <a href="feature-records.php" class="text-decoration-none">
            <div class="card p-3 text-center h-100">
                <i class="fas fa-file-medical fa-3x mb-3" style="color: #87CEEB;"></i>
                <h5>Digital Records</h5>
                <p>Access your treatment history and prescriptions anytime.</p>
            </div>
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>