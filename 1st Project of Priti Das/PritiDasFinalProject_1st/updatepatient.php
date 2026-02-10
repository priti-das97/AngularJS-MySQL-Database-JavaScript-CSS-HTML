<?php
// updatepatient.php

ini_set('display_errors', 1);   // set to 0 in production
error_reporting(E_ALL);

if (ob_get_length()) {
    ob_clean();
}

header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "pritidas";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed: " . $conn->connect_error]);
    exit();
}

$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

if ($data === null) {
    echo json_encode([
        "success" => false,
        "error"   => "Invalid JSON body",
        "raw"     => $raw
    ]);
    exit();
}

/*
   Angular sends:

   patient_id, p_name, age, gender,
   contact_info, address, doctor_id, room_id
*/

$patient_id = $data['patient_id'] ?? $data['id'] ?? null;  // accept both for safety

if ($patient_id === null || $patient_id === '') {
    echo json_encode(["success" => false, "error" => "Patient ID is required"]);
    exit();
}

$patient_id   = (int)$patient_id;
$p_name       = $data['p_name']        ?? ($data['name'] ?? '');
$age          = isset($data['age']) ? (int)$data['age'] : 0;
$gender       = $data['gender']        ?? '';
$contact_info = $data['contact_info']  ?? ($data['contact'] ?? '');
$address      = $data['address']       ?? '';
$doctor_id    = isset($data['doctor_id']) ? (int)$data['doctor_id'] : 0;
$room_id      = isset($data['room_id'])   ? (int)$data['room_id']   : 0;

/*
   DB columns are:

   patient_id, p_name, age, gender,
   contact_info, address, doctor_id, room_id
*/
$sql = "UPDATE patient
        SET p_name = ?, age = ?, gender = ?, contact_info = ?, address = ?, doctor_id = ?, room_id = ?
        WHERE patient_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
    exit();
}

// sisssiii = string, int, string, string, string, int, int, int
$stmt->bind_param(
    "sisssiii",
    $p_name,
    $age,
    $gender,
    $contact_info,
    $address,
    $doctor_id,
    $room_id,
    $patient_id
);

if ($stmt->execute()) {
    echo json_encode([
        "success"       => true,
        "rows_affected" => $stmt->affected_rows,
        "patient"       => [
            "patient_id"   => $patient_id,
            "p_name"       => $p_name,
            "age"          => $age,
            "gender"       => $gender,
            "contact_info" => $contact_info,
            "address"      => $address,
            "doctor_id"    => $doctor_id,
            "room_id"      => $room_id
        ]
    ]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
