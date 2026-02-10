 <?php
// deletepatient.php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "pritidas");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit();
}

// patient_id via POST (Angular) or GET
$patient_id = $_POST['patient_id'] ?? $_GET['patient_id'] ?? null;

if ($patient_id === null || $patient_id === '') {
    echo json_encode([
        "success" => false,
        "error"   => "patient_id is missing on server"
    ]);
    exit();
}

$patient_id = (int)$patient_id;

/* again, make sure table / col names match */
$stmt = $conn->prepare("DELETE FROM patient WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
