<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('admin');

$appointments = $conn->query("SELECT a.*, u.name as patient_name FROM appointments a JOIN users u ON a.patient_id = u.id ORDER BY a.appointment_date DESC, a.appointment_time DESC");

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h2>All Appointments</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Dentist</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $appointments->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($row['appointment_date'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['dentist_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>