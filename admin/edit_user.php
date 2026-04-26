<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('admin');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id <= 0) {
    header("Location: users.php");
    exit();
}

// Fetch user data
$stmt = $conn->prepare("SELECT name, email, phone, role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows == 0) {
    header("Location: users.php");
    exit();
}
$user = $result->fetch_assoc();

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    $new_password = $_POST['new_password'];
    
    if(empty($name) || empty($email) || empty($role)) {
        $error = "Name, email, and role are required.";
    } else {
        // Check if email already used by another user
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check->bind_param("si", $email, $id);
        $check->execute();
        $check_result = $check->get_result();
        if($check_result->num_rows > 0) {
            $error = "Email already exists for another user.";
        } else {
            if(!empty($new_password)) {
                if(strlen($new_password) < 6) {
                    $error = "Password must be at least 6 characters.";
                } else {
                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $update = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, role=?, password=? WHERE id=?");
                    $update->bind_param("sssssi", $name, $email, $phone, $role, $hashed, $id);
                }
            } else {
                $update = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, role=? WHERE id=?");
                $update->bind_param("ssssi", $name, $email, $phone, $role, $id);
            }
            if($update->execute()) {
                $success = "User updated successfully.";
                // Refresh user data
                $user['name'] = $name;
                $user['email'] = $email;
                $user['phone'] = $phone;
                $user['role'] = $role;
            } else {
                $error = "Error: " . $conn->error;
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
                <h4 class="mb-0">Edit User</h4>
            </div>
            <div class="card-body">
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="patient" <?php echo $user['role']=='patient'?'selected':''; ?>>Patient</option>
                            <option value="doctor" <?php echo $user['role']=='doctor'?'selected':''; ?>>Doctor</option>
                            <option value="staff" <?php echo $user['role']=='staff'?'selected':''; ?>>Staff</option>
                            <option value="admin" <?php echo $user['role']=='admin'?'selected':''; ?>>Admin</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" name="new_password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="users.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>