<?php
// Start session and include database connection
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DentConnect - Dental Clinic Management</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 (free icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/dentconnect/assets/css/style.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #87CEEB;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/dentconnect/index.php">
            <i class="fas fa-tooth me-2"></i>DentConnect
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['role'] == 'patient'): ?>
                        <!-- Patient Menu -->
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/patient/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/patient/book.php"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/patient/history.php"><i class="fas fa-history"></i> History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/patient/xray.php"><i class="fas fa-x-ray"></i> X-Ray AI</a>
                        </li>
                    <?php elseif($_SESSION['role'] == 'doctor'): ?>
                        <!-- Doctor Menu -->
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/doctor/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                        </li>
                        <!-- Additional doctor links can be added here -->
                    <?php elseif($_SESSION['role'] == 'staff'): ?>
                        <!-- Staff Menu -->
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/staff/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/staff/appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/staff/patients.php"><i class="fas fa-users"></i> Patients</a>
                        </li>
                    <?php elseif($_SESSION['role'] == 'admin'): ?>
                        <!-- Admin Menu -->
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/admin/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/admin/users.php"><i class="fas fa-users-cog"></i> Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/dentconnect/admin/appointments.php"><i class="fas fa-calendar-alt"></i> Appointments</a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- User Dropdown Menu (replaces separate username and logout) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/dentconnect/profile.php"><i class="fas fa-id-card"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="/dentconnect/settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/dentconnect/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Not logged in menu -->
                    <li class="nav-item"><a class="nav-link" href="/dentconnect/index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown">Login</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/dentconnect/patient/login.php">Patient</a></li>
                            <li><a class="dropdown-item" href="/dentconnect/doctor/login.php">Doctor</a></li>
                            <li><a class="dropdown-item" href="/dentconnect/staff/login.php">Staff</a></li>
                            <li><a class="dropdown-item" href="/dentconnect/admin/login.php">Admin</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/dentconnect/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content Container -->
<div class="container mt-4">