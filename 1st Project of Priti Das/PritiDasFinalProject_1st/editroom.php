<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost","root","","pritidas");
if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
if ($data === null) {
    echo json_encode(["error" => "Invalid JSON"]);
    exit();
}

$room_number  = $data['room_number']  ?? '';
$room_type    = $data['room_type']    ?? '';
$is_available = (int)($data['is_available'] ?? 0);
$room_price   = (float)($data['room_price'] ?? 0);

$stmt = $conn->prepare(
  "INSERT INTO room (room_number, room_type, is_available, room_price)
   VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssid", $room_number, $room_type, $is_available, $room_price);
//                ^^ fixed types

if ($stmt->execute()) {
    $id = $conn->insert_id;
    echo json_encode([
        "id"           => $id,
        "room_number"  => $room_number,
        "room_type"    => $room_type,
        "is_available" => $is_available,
        "room_price"   => $room_price
    ]);
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$conn->close();
