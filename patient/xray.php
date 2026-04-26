<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('patient');

$patient_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['xray_image'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = time() . '_' . basename($_FILES['xray_image']['name']);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is actual image
    $check = getimagesize($_FILES['xray_image']['tmp_name']);
    if ($check === false) {
        $error = "File is not an image.";
    } elseif ($_FILES['xray_image']['size'] > 5000000) { // 5MB limit
        $error = "File too large (max 5MB).";
    } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $error = "Only JPG, JPEG, PNG & GIF files allowed.";
    } else {
        if (move_uploaded_file($_FILES['xray_image']['tmp_name'], $target_file)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO xray_uploads (patient_id, image_path) VALUES (?, ?)");
            $stmt->bind_param("is", $patient_id, $file_name);
            if ($stmt->execute()) {
                $upload_id = $stmt->insert_id;
                header("Location: xray_result.php?id=" . $upload_id);
                exit();
            } else {
                $error = "Database error: " . $conn->error;
            }
        } else {
            $error = "Error uploading file.";
        }
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-x-ray me-2"></i>AI X-Ray Analysis</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">Upload a dental X-ray image and our AI will analyze it for potential issues. (Demo version: mock results shown.)</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="xray_image" class="form-label">Select X-ray Image</label>
                        <input type="file" class="form-control" name="xray_image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload & Analyze</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>