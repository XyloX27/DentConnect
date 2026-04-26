<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('patient');

$patient_id = $_SESSION['user_id'];
$appointment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($appointment_id <= 0) {
    header("Location: dashboard.php");
    exit();
}

// Check that appointment belongs to patient and is not in the past
$check = $conn->prepare("SELECT id, appointment_date FROM appointments WHERE id = ? AND patient_id = ? AND status IN ('pending', 'confirmed')");
$check->bind_param("ii", $appointment_id, $patient_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $today = date('Y-m-d');
    if ($row['appointment_date'] < $today) {
        $_SESSION['error'] = "Cannot cancel past appointments.";
    } else {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND patient_id = ?");
        $stmt->bind_param("ii", $appointment_id, $patient_id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Appointment cancelled successfully.";
        } else {
            $_SESSION['error'] = "Unable to cancel appointment.";
        }
    }
} else {
    $_SESSION['error'] = "Appointment not found or cannot be cancelled.";
}

header("Location: dashboard.php");
exit();
?>