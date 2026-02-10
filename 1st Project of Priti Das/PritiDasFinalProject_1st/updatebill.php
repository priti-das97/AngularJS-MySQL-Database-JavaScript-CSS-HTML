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

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['bill_id'])) {
    echo json_encode(["error" => "Bill ID is required"]);
    exit();
}

$bill_id        = (int)$data['bill_id'];
$patient_id     = (int)$data['patient_id'];
$services_tax   = floatval(str_replace("%","",$data['services_tax']));
$total_amount   = (float)$data['total_amount'];
$paid_bill      = (float)$data['paid_bill'];
$remaining_bill = (float)$data['remaining_bill'];

$sql = "UPDATE bill
        SET patient_id=?, services_tax=?, total_amount=?, paid_bill=?, remaining_bill=?
        WHERE bill_id=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iddddi",
    $patient_id,
    $services_tax,
    $total_amount,
    $paid_bill,
    $remaining_bill,
    $bill_id
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
