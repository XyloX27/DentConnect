<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('doctor');

$doctor_name = $_SESSION['user_name']; // assumes doctor's name matches dentist_name in appointments

$today = date('Y-m-d');

// Get today's appointments for this doctor
$stmt = $conn->prepare("SELECT a.*, u.name as patient_name FROM appointments a JOIN users u ON a.patient_id = u.id WHERE a.dentist_name = ? AND a.appointment_date = ? ORDER BY a.appointment_time");
$stmt->bind_param("ss", $doctor_name, $today);
$stmt->execute();
$today_appointments = $stmt->get_result();

// Get upcoming appointments (future) for this doctor
$upcoming = $conn->prepare("SELECT a.*, u.name as patient_name FROM appointments a JOIN users u ON a.patient_id = u.id WHERE a.dentist_name = ? AND a.appointment_date > ? ORDER BY a.appointment_date, a.appointment_time LIMIT 10");
$upcoming->bind_param("ss", $doctor_name, $today);
$upcoming->execute();
$upcoming_result = $upcoming->get_result();

include '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Doctor Dashboard</h2>
        <p class="text-muted">Welcome, Dr. <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
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
                                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                    <td>
                                        <?php
                                        $badge = 'secondary';
                                        if ($row['status'] == 'confirmed') $badge = 'success';
                                        elseif ($row['status'] == 'pending') $badge = 'warning';
                                        elseif ($row['status'] == 'completed') $badge = 'info';
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>"><?php echo $row['status']; ?></span>
                                    </td>
                                    <td>
                                        <a href="update_status.php?id=<?php echo $row['id']; ?>&status=completed" class="btn btn-sm btn-info">Mark Completed</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No appointments today.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Appointments -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Upcoming Appointments</h5>
            </div>
            <div class="card-body">
                <?php if ($upcoming_result->num_rows > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $upcoming_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($row['appointment_date'])); ?></td>
                                    <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                    <td><span class="badge bg-warning"><?php echo $row['status']; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No upcoming appointments.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>