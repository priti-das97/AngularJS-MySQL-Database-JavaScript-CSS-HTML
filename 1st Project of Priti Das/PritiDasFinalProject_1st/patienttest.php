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

// Fetch all patient details
$sql = "SELECT 
            patient_id, 
            p_name, 
            age, 
            gender, 
            contact_info, 
            address, 
            doctor_id, 
            room_id 
        FROM patient";

$result = $conn->query($sql);

$patients = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}

echo json_encode($patients);
$conn->close();
?>
