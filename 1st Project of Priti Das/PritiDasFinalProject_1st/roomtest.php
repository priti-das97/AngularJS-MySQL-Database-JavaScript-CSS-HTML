<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "pritidas";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode([]);
    exit();
}

// Fetch all room details
$sql = "SELECT 
            room_id AS id, 
            room_number, 
            room_type, 
            is_available, 
            room_price 
        FROM room";

$result = $conn->query($sql);

$rooms = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

echo json_encode($rooms);
$conn->close();
?>
