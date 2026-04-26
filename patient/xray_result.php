<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireRole('patient');

$patient_id = $_SESSION['user_id'];
$upload_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch upload record
$stmt = $conn->prepare("SELECT * FROM xray_uploads WHERE id = ? AND patient_id = ?");
$stmt->bind_param("ii", $upload_id, $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: xray.php");
    exit();
}

$upload = $result->fetch_assoc();

// Generate mock AI results based on image name or random
$findings = [
    [
        'area' => 'Tooth 14',
        'issue' => 'Cavity detected',
        'confidence' => rand(75, 98)
    ],
    [
        'area' => 'Tooth 18',
        'issue' => 'Bone loss',
        'confidence' => rand(70, 95)
    ],
    [
        'area' => 'Tooth 32',
        'issue' => 'Impacted wisdom tooth',
        'confidence' => rand(80, 99)
    ]
];

// Add some randomness based on upload_id
if ($upload_id % 2 == 0) {
    $findings[] = [
        'area' => 'General',
        'issue' => 'Normal variation, no concern',
        'confidence' => 65
    ];
}

// Save results to database (optional)
$ai_result_json = json_encode($findings);
$update = $conn->prepare("UPDATE xray_uploads SET ai_result = ? WHERE id = ?");
$update->bind_param("si", $ai_result_json, $upload_id);
$update->execute();

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h2>AI Analysis Results</h2>
        <p class="text-muted">Uploaded on: <?php echo date('d M Y H:i', strtotime($upload['uploaded_at'])); ?></p>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Uploaded X-Ray</h5>
            </div>
            <div class="card-body text-center">
                <img src="../uploads/<?php echo htmlspecialchars($upload['image_path']); ?>" alt="X-Ray" class="img-fluid rounded" style="max-height: 400px;">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">AI Findings</h5>
            </div>
            <div class="card-body">
                <?php foreach ($findings as $f): ?>
                    <div class="ai-finding-item">
                        <strong><?php echo htmlspecialchars($f['area']); ?>:</strong> <?php echo htmlspecialchars($f['issue']); ?>
                        <div class="mt-2">
                            <small>Confidence: <?php echo $f['confidence']; ?>%</small>
                            <div class="ai-confidence">
                                <div class="ai-confidence-fill" style="width: <?php echo $f['confidence']; ?>%;"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <a href="xray.php" class="btn btn-primary">Analyze Another</a>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>