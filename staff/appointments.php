<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('staff');

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT a.*, u.name as patient_name FROM appointments a JOIN users u ON a.patient_id = u.id";
if ($status_filter) {
    $query .= " WHERE a.status = '" . $conn->real_escape_string($status_filter) . "'";
}
$query .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$appointments = $conn->query($query);

include '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Manage Appointments</h2>
        <div class="btn-group mb-3">
            <a href="appointments.php" class="btn btn-secondary <?php echo !$status_filter ? 'active' : ''; ?>">All</a>
            <a href="appointments.php?status=pending" class="btn btn-warning <?php echo $status_filter=='pending' ? 'active' : ''; ?>">Pending</a>
            <a href="appointments.php?status=confirmed" class="btn btn-success <?php echo $status_filter=='confirmed' ? 'active' : ''; ?>">Confirmed</a>
            <a href="appointments.php?status=completed" class="btn btn-info <?php echo $status_filter=='completed' ? 'active' : ''; ?>">Completed</a>
            <a href="appointments.php?status=cancelled" class="btn btn-danger <?php echo $status_filter=='cancelled' ? 'active' : ''; ?>">Cancelled</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?php if ($appointments->num_rows > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Dentist</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
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
                    <p class="text-muted">No appointments found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>