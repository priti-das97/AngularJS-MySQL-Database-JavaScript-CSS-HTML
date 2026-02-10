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
$name = $conn->real_escape_string($data['name']);
$specialization = $conn->real_escape_string($data['specialization']);
$phone = $conn->real_escape_string($data['phone']);
$email = $conn->real_escape_string($data['email']); // now used

// Insert including email
$sql = "INSERT INTO doctor (dr_name, specialization, contact_info, email) 
        VALUES ('$name', '$specialization', '$phone', '$email')";

if ($conn->query($sql) === TRUE) {
    $id = $conn->insert_id; // auto increment ID
    echo json_encode([
        "id" => $id,
        "name" => $name,
        "specialization" => $specialization,
        "phone" => $phone,
        "email" => $email
    ]);
} else {
    echo json_encode(["error" => $conn->error]);
}

$conn->close();
?>
