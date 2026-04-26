<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('staff');

$patient_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch patient info
$patient_stmt = $conn->prepare("SELECT id, name, email, phone, created_at FROM users WHERE id = ? AND role='patient'");
$patient_stmt->bind_param("i", $patient_id);
$patient_stmt->execute();
$patient_result = $patient_stmt->get_result();
if ($patient_result->num_rows == 0) {
    header("Location: patients.php");
    exit();
}
$patient = $patient_result->fetch_assoc();

// Fetch appointments
$appointments = $conn->prepare("SELECT * FROM appointments WHERE patient_id = ? ORDER BY appointment_date DESC, appointment_time DESC");
$appointments->bind_param("i", $patient_id);
$appointments->execute();
$appointments_result = $appointments->get_result();

include '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Patient Details: <?php echo htmlspecialchars($patient['name']); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone'] ?: 'Not provided'); ?></p>
        <p><strong>Registered:</strong> <?php echo date('d M Y', strtotime($patient['created_at'])); ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Appointment History</h4>
        <?php if ($appointments_result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Dentist</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $appointments_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($row['appointment_date'])); ?></td>
                            <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
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
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No appointments for this patient.</p>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <a href="patients.php" class="btn btn-secondary">Back to Patients</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>