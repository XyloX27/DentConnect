<?php
require_once '../includes/config.php';
require_once '../includes/auth.php'; // We'll create this next to check if user is logged in as patient

// Ensure user is logged in and is a patient
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: ../login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Get upcoming appointments (future dates)
$upcoming = $conn->prepare("SELECT * FROM appointments WHERE patient_id = ? AND appointment_date >= CURDATE() ORDER BY appointment_date, appointment_time LIMIT 5");
$upcoming->bind_param("i", $patient_id);
$upcoming->execute();
$upcoming_result = $upcoming->get_result();

// Get total appointments count
$total = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE patient_id = ?");
$total->bind_param("i", $patient_id);
$total->execute();
$total_result = $total->get_result();
$total_count = $total_result->fetch_assoc()['total'];

// Get last visit
$last = $conn->prepare("SELECT appointment_date FROM appointments WHERE patient_id = ? AND appointment_date < CURDATE() ORDER BY appointment_date DESC LIMIT 1");
$last->bind_param("i", $patient_id);
$last->execute();
$last_result = $last->get_result();
$last_visit = $last_result->fetch_assoc()['appointment_date'] ?? 'None';

include '../includes/header.php';
if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif;

?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
        <p class="text-muted">Manage your dental appointments and health records.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <i class="fas fa-calendar-check"></i>
            <h3 class="mt-2"><?php echo $upcoming_result->num_rows; ?></h3>
            <p>Upcoming Appointments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <i class="fas fa-history"></i>
            <h3 class="mt-2"><?php echo $total_count; ?></h3>
            <p>Total Appointments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <i class="fas fa-calendar-alt"></i>
            <h3 class="mt-2"><?php echo $last_visit; ?></h3>
            <p>Last Visit</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-12">
        <a href="book.php" class="btn btn-primary me-2"><i class="fas fa-plus-circle"></i> Book New Appointment</a>
        <a href="history.php" class="btn btn-secondary me-2"><i class="fas fa-list"></i> View History</a>
        <a href="xray.php" class="btn btn-info" style="background-color: #E6E6FA; border-color: #E6E6FA;"><i class="fas fa-x-ray"></i> AI X-Ray Analysis</a>
    </div>
</div>

<!-- Upcoming Appointments Table -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Upcoming Appointments</h5>
            </div>
            <div class="card-body">
                <?php if($upcoming_result->num_rows > 0): ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Dentist</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $upcoming_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($row['appointment_date'])); ?></td>
                                    <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['dentist_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                    <td>
                                        <?php
                                        $badge = 'secondary';
                                        if($row['status'] == 'confirmed') $badge = 'success';
                                        elseif($row['status'] == 'pending') $badge = 'warning';
                                        elseif($row['status'] == 'cancelled') $badge = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>"><?php echo $row['status']; ?></span>
                                    </td>
                                    <td>
                                        <a href="cancel.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this appointment?');">Cancel</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No upcoming appointments. <a href="book.php">Book one now!</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>