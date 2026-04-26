<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

$email = trim($_POST['email']);
$password = $_POST['password'];
$requested_role = $_POST['role']; // from hidden field

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Email and password required.";
    header("Location: $requested_role/login.php");
    exit();
}

// Fetch user
$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    $_SESSION['login_error'] = "Invalid email or password.";
    header("Location: $requested_role/login.php");
    exit();
}

$user = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = "Invalid email or password.";
    header("Location: $requested_role/login.php");
    exit();
}

// Check if role matches the requested login page
if ($user['role'] != $requested_role) {
    $_SESSION['login_error'] = "You are not authorized to login from this portal.";
    header("Location: $requested_role/login.php");
    exit();
}

// Set session
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['role'] = $user['role'];

// Redirect to appropriate dashboard
switch ($user['role']) {
    case 'patient':
        header("Location: patient/dashboard.php");
        break;
    case 'doctor':
        header("Location: doctor/dashboard.php");
        break;
    case 'staff':
        header("Location: staff/dashboard.php");
        break;
    case 'admin':
        header("Location: admin/dashboard.php");
        break;
    default:
        header("Location: index.php");
}
exit();
?>