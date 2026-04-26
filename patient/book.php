<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('patient');

$patient_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Fetch all doctors from users table
$doctors_result = $conn->query("SELECT name FROM users WHERE role = 'doctor' ORDER BY name");
$doctors = [];
while ($row = $doctors_result->fetch_assoc()) {
    $doctors[] = $row['name'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dentist_name = trim($_POST['dentist_name']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $reason = trim($_POST['reason']);

    if (empty($dentist_name) || empty($appointment_date) || empty($appointment_time) || empty($reason)) {
        $error = "All fields are required.";
    } else {
        // Check if slot is already booked
        $check = $conn->prepare("SELECT id FROM appointments WHERE dentist_name = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'");
        $check->bind_param("sss", $dentist_name, $appointment_date, $appointment_time);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $error = "This time slot is already booked. Please choose another.";
        } else {
            $stmt = $conn->prepare("INSERT INTO appointments (patient_id, dentist_name, appointment_date, appointment_time, reason, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("issss", $patient_id, $dentist_name, $appointment_date, $appointment_time, $reason);
            if ($stmt->execute()) {
                $message = "Appointment booked successfully! It is pending confirmation.";
            } else {
                $error = "Error booking appointment: " . $conn->error;
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Book a New Appointment</h4>
            </div>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="dentist_name" class="form-label">Select Dentist</label>
                        <select class="form-select" name="dentist_name" required>
                            <option value="">-- Choose Dentist --</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?php echo htmlspecialchars($doctor); ?>"><?php echo htmlspecialchars($doctor); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="appointment_date" class="form-label">Date</label>
                            <input type="date" class="form-control" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="appointment_time" class="form-label">Time</label>
                            <input type="time" class="form-control" name="appointment_time" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Visit</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="Brief description of your issue..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>