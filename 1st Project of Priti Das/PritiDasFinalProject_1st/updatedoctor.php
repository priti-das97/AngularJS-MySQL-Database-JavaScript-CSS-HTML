<?php
// updatedoctor.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

$conn = new mysqli("localhost", "root", "", "pritidas");
if ($conn->connect_error) {
  echo json_encode(["success" => false, "error" => "DB connection failed: " . $conn->connect_error]);
  exit;
}

$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

if ($data === null) {
  echo json_encode(["success" => false, "error" => "Invalid JSON", "raw" => $raw]);
  exit;
}

if (!isset($data['id'])) {              // Angular sends `id`
  echo json_encode(["success" => false, "error" => "Doctor id is required"]);
  exit;
}

/*
   Angular fields       ->   DB columns
   ------------------------------------------
   id                   ->   doctor_id
   name                 ->   dr_name
   specialization       ->   specialization
   phone                ->   contact_info
   email                ->   email
*/

$doctor_id     = (int)$data['id'];
$dr_name       = $data['name']           ?? '';
$specialization= $data['specialization'] ?? '';
$contact_info  = $data['phone']          ?? '';
$email         = $data['email']          ?? '';

$sql = "UPDATE doctor
        SET dr_name = ?, specialization = ?, contact_info = ?, email = ?
        WHERE doctor_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
  exit;
}

$stmt->bind_param("ssssi", $dr_name, $specialization, $contact_info, $email, $doctor_id);

if ($stmt->execute()) {
  echo json_encode([
    "success"       => true,
    "rows_affected" => $stmt->affected_rows,
    "doctor"        => [
      "id"             => $doctor_id,      // send back in Angular field names
      "name"           => $dr_name,
      "specialization" => $specialization,
      "phone"          => $contact_info,
      "email"          => $email
    ]
  ]);
} else {
  echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
