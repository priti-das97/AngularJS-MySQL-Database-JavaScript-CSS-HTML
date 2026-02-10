<?php
// updateroom.php

ini_set('display_errors', 0);
error_reporting(0);

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

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "error" => "Room ID (id) is required"]);
    exit();
}

// JSON field is still called "id", DB column is "room_id"
$room_id      = (int)$data['id'];
$room_number  = $data['room_number']  ?? '';
$room_type    = $data['room_type']    ?? '';
$is_available = (int)($data['is_available'] ?? 0);
$room_price   = (float)($data['room_price'] ?? 0);

$sql = "UPDATE room
        SET room_number = ?, room_type = ?, is_available = ?, room_price = ?
        WHERE room_id = ?";   // <-- changed here

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
    exit();
}

$stmt->bind_param("ssidi", $room_number, $room_type, $is_available, $room_price, $room_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "rows_affected" => $stmt->affected_rows]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
