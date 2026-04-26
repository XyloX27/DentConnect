<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('admin');

// Get counts
$total_patients = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='patient'")->fetch_assoc()['c'];
$total_doctors = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='doctor'")->fetch_assoc()['c'];
$total_staff = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='staff'")->fetch_assoc()['c'];
$total_appointments = $conn->query("SELECT COUNT(*) as c FROM appointments")->fetch_assoc()['c'];

include '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Admin Dashboard</h2>
        <p class="text-muted">System Overview</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-user-injured"></i>
            <h3 class="mt-2"><?php echo $total_patients; ?></h3>
            <p>Patients</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-user-md"></i>
            <h3 class="mt-2"><?php echo $total_doctors; ?></h3>
            <p>Doctors</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3 class="mt-2"><?php echo $total_staff; ?></h3>
            <p>Staff</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-calendar-check"></i>
            <h3 class="mt-2"><?php echo $total_appointments; ?></h3>
            <p>Total Appointments</p>
        </div>
    </div>
</div>

<!-- Quick links -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Management</h5>
            </div>
            <div class="card-body">
                <a href="users.php" class="btn btn-primary me-2">Manage Users</a>
                <a href="appointments.php" class="btn btn-secondary">View All Appointments</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>