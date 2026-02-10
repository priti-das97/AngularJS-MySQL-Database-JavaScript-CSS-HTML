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

// Validate data
if (!isset($data['patient_id']) || $data['patient_id'] === '') {
    echo json_encode(["error" => "Patient ID is required"]);
    exit();
}

$patient_id    = (int)$data['patient_id'];
$services_tax  = floatval(str_replace("%","",$data['services_tax']));
$total_amount  = (float)$data['total_amount'];
$paid_bill     = (float)$data['paid_bill'];
$remaining_bill= (float)$data['remaining_bill'];

// Insert into DB
$sql = "INSERT INTO bill (patient_id, services_tax, total_amount, paid_bill, remaining_bill)
        VALUES ('$patient_id', '$services_tax', '$total_amount', '$paid_bill', '$remaining_bill')";

if ($conn->query($sql) === TRUE) {
    $id = $conn->insert_id;
    echo json_encode([
        "bill_id" => $id,
        "patient_id" => $patient_id,
        "services_tax" => $services_tax,
        "total_amount" => $total_amount,
        "paid_bill" => $paid_bill,
        "remaining_bill" => $remaining_bill
    ]);
} else {
    echo json_encode(["error" => $conn->error]);
}

$conn->close();
?>
