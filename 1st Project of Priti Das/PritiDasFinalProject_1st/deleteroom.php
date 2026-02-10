<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "pritidas");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit();
}

// read from GET: deleteroom.php?room_id=XX
// Read `room_id` from POST first, fallback to GET if needed
$room_id = $_POST['room_id'] ?? $_GET['room_id'] ?? null;


if ($room_id === null || $room_id === '') {
    echo json_encode([
        "success" => false,
        "error"  => "room_id is missing on server (debug-msg)"
    ]);
    exit();
}

$room_id = (int)$room_id;

$stmt = $conn->prepare("DELETE FROM room WHERE room_id = ?");
$stmt->bind_param("i", $room_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
