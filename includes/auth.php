<?php
// Authentication check
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Optional role check
function requireRole($role) {
    if ($_SESSION['role'] != $role) {
        header("Location: ../index.php");
        exit();
    }
}
?>