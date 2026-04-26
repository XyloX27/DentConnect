<?php
require_once '../includes/config.php';
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
$role = 'doctor';
$error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']);
include '../includes/header.php';
include '../includes/login_form.php';
include '../includes/footer.php';
?>