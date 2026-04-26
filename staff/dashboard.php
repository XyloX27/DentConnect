<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('staff');

$today = date('Y-m-d');

// Get today's appointments
$today_stmt = $conn->prepare("SELECT a.*, u.name as patient_name FROM appointments a JOIN users u ON a.patient_id = u.id WHERE a.appointment_date = ? ORDER BY a.appointment_time");
$today_stmt->bind_param("s", $today);
$today_stmt->execute();
$today_appointments = $today_stmt->get_result();

// Get counts
$total_patients = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='patient'")->fetch_assoc()['total'];
$total_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments")->fetch_assoc()['total'];
$pending_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status='pending'")->fetch_assoc()['total'];

include '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Staff Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3 class="mt-2"><?php echo $total_patients; ?></h3>
            <p>Total Patients</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <i class="fas fa-calendar-check"></i>
            <h3 class="mt-2"><?php echo $total_appointments; ?></h3>
            <p>Total Appointments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <i class="fas fa-clock"></i>
            <h3 class="mt-2"><?php echo $pending_appointments; ?></h3>
            <p>Pending Confirmation</p>
        </div>
    </div>
</div>

<!-- Today's Appointments -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Today's Appointments (<?php echo date('d M Y'); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if ($today_appointments->num_rows > 0): ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Dentist</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $today_appointments->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dentist_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                    <td>
                                        <?php
                                        $badge = 'secondary';
                                        if ($row['status'] == 'confirmed') $badge = 'success';
                                        elseif ($row['status'] == 'pending') $badge = 'warning';
                                        elseif ($row['status'] == 'completed') $badge = 'info';
                                        elseif ($row['status'] == 'cancelled') $badge = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>"><?php echo $row['status']; ?></span>
                                    </td>
                                    <td>
                                        <a href="update_appointment.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Update</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No appointments scheduled for today.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>