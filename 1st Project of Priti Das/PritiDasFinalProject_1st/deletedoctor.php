<?php
// deletedoctor.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

$conn = new mysqli("localhost", "root", "", "pritidas");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit;
}

/*
  Angular sends: id  (doctor.id)
  DB column is : doctor_id

  Accept both just in case.
*/
$doctor_id = $_POST['id']
    ?? $_POST['doctor_id']
    ?? $_GET['id']
    ?? $_GET['doctor_id']
    ?? null;

if ($doctor_id === null || $doctor_id === '') {
    echo json_encode([
        "success" => false,
        "error"   => "doctor_id (id) is missing on server"
    ]);
    exit;
}

$doctor_id = (int)$doctor_id;

$stmt = $conn->prepare("DELETE FROM doctor WHERE doctor_id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $doctor_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
