<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('doctor');

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];
    $allowed = ['confirmed', 'completed'];
    if (in_array($status, $allowed)) {
        $doctor_name = $_SESSION['user_name'];
        $check = $conn->prepare("SELECT id FROM appointments WHERE id = ? AND dentist_name = ?");
        $check->bind_param("is", $id, $doctor_name);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();
        }
    }
}
header("Location: dashboard.php");
exit();
?>