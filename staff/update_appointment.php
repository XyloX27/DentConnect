<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('staff');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';
$success = '';

// Fetch appointment details
$stmt = $conn->prepare("SELECT a.*, u.name as patient_name FROM appointments a JOIN users u ON a.patient_id = u.id WHERE a.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    header("Location: appointments.php");
    exit();
}
$appointment = $result->fetch_assoc();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];
    $update = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $update->bind_param("si", $new_status, $id);
    if ($update->execute()) {
        $success = "Status updated successfully.";
        $appointment['status'] = $new_status; // refresh display
    } else {
        $error = "Error updating status.";
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Update Appointment Status</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <p><strong>Patient:</strong> <?php echo htmlspecialchars($appointment['patient_name']); ?></p>
                <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($appointment['appointment_date'])); ?> at <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></p>
                <p><strong>Dentist:</strong> <?php echo htmlspecialchars($appointment['dentist_name']); ?></p>
                <p><strong>Reason:</strong> <?php echo htmlspecialchars($appointment['reason']); ?></p>
                <p><strong>Current Status:</strong> 
                    <span class="badge bg-<?php 
                        if($appointment['status']=='confirmed') echo 'success';
                        elseif($appointment['status']=='pending') echo 'warning';
                        elseif($appointment['status']=='completed') echo 'info';
                        elseif($appointment['status']=='cancelled') echo 'danger';
                        else echo 'secondary';
                    ?>"><?php echo $appointment['status']; ?></span>
                </p>

                <form method="POST">
                    <div class="mb-3">
                        <label for="status" class="form-label">Change Status</label>
                        <select class="form-select" name="status" required>
                            <option value="pending" <?php echo $appointment['status']=='pending'?'selected':''; ?>>Pending</option>
                            <option value="confirmed" <?php echo $appointment['status']=='confirmed'?'selected':''; ?>>Confirmed</option>
                            <option value="completed" <?php echo $appointment['status']=='completed'?'selected':''; ?>>Completed</option>
                            <option value="cancelled" <?php echo $appointment['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                    <a href="appointments.php" class="btn btn-secondary">Back to Appointments</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>