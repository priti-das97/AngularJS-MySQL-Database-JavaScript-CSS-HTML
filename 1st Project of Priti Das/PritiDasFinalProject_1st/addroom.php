<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "pritidas";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$room_number = $conn->real_escape_string($data['room_number']);
$room_type = $conn->real_escape_string($data['room_type']);
$is_available = intval($data['is_available']); // 0 or 1
$room_price = $conn->real_escape_string($data['room_price']);

// Insert room
$sql = "INSERT INTO room (room_number, room_type, is_available, room_price)
        VALUES ('$room_number', '$room_type', '$is_available', '$room_price')";

if ($conn->query($sql) === TRUE) {
    $id = $conn->insert_id; // auto-increment ID
    echo json_encode([
        "id" => $id,
        "room_number" => $room_number,
        "room_type" => $room_type,
        "is_available" => $is_available,
        "room_price" => $room_price
    ]);
} else {
    echo json_encode(["error" => $conn->error]);
}

$conn->close();
?>
