<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('patient');

$patient_id = $_SESSION['user_id'];

// Get past appointments (including today if time passed? We'll treat past as date < today)
$past = $conn->prepare("SELECT * FROM appointments WHERE patient_id = ? AND appointment_date < CURDATE() ORDER BY appointment_date DESC, appointment_time DESC");
$past->bind_param("i", $patient_id);
$past->execute();
$past_result = $past->get_result();

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h2>Appointment History</h2>
        <p class="text-muted">Your past appointments and treatments.</p>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if ($past_result->num_rows > 0): ?>
            <div class="table-responsive">
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
                        <?php while ($row = $past_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($row['appointment_date'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                                <td><?php echo htmlspecialchars($row['dentist_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td>
                                    <?php
                                    $badge = 'secondary';
                                    if ($row['status'] == 'completed') $badge = 'success';
                                    elseif ($row['status'] == 'cancelled') $badge = 'danger';
                                    elseif ($row['status'] == 'confirmed') $badge = 'info';
                                    ?>
                                    <span class="badge bg-<?php echo $badge; ?>"><?php echo $row['status']; ?></span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No past appointments found.</div>
        <?php endif; ?>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>