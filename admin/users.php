<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('admin');

// Handle delete action if any
if(isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    // Prevent admin from deleting themselves
    if($delete_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
    }
    header("Location: users.php");
    exit();
}

// Fetch all users
$users = $conn->query("SELECT id, name, email, phone, role, created_at FROM users ORDER BY FIELD(role, 'admin', 'doctor', 'staff', 'patient'), name");

include '../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Manage Users</h2>
        <a href="add_user.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Add New User</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone'] ?: 'N/A'); ?></td>
                                <td><?php echo ucfirst($row['role']); ?></td>
                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                    <?php if($row['id'] != $_SESSION['user_id']): ?>
                                        <a href="users.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i> Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>