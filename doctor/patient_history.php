<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('doctor');

$doctor_name = $_SESSION['user_name'];
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

// Verify this doctor has treated this patient (by checking appointments)
$check = $conn->prepare("SELECT id FROM appointments WHERE dentist_name = ? AND patient_id = ? LIMIT 1");
$check->bind_param("si", $doctor_name, $patient_id);
$check->execute();
if ($check->get_result()->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}

// Get patient details
$stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();

// Get appointment history
$apt = $conn->prepare("SELECT * FROM appointments WHERE patient_id = ? AND dentist_name = ? ORDER BY appointment_date DESC");
$apt->bind_param("is", $patient_id, $doctor_name);
$apt->execute();
$appointments = $apt->get_result();

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h2>Patient History: <?php echo htmlspecialchars($patient['name']); ?></h2>
        <p class="text-muted">Email: <?php echo htmlspecialchars($patient['email']); ?> | Phone: <?php echo htmlspecialchars($patient['phone'] ?: 'N/A'); ?></p>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Appointment History</h5>
            </div>
            <div class="card-body">
                <?php if ($appointments->num_rows > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($a = $appointments->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($a['appointment_date'])); ?></td>
                                    <td><?php echo date('h:i A', strtotime($a['appointment_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($a['reason']); ?></td>
                                    <td>
                                        <?php
                                        $badge = 'secondary';
                                        if ($a['status'] == 'completed') $badge = 'success';
                                        elseif ($a['status'] == 'cancelled') $badge = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>"><?php echo $a['status']; ?></span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No appointment history.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>