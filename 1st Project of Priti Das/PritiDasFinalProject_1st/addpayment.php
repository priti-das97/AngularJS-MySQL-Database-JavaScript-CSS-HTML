<?php
// save_payment.php
date_default_timezone_set('Asia/Kolkata');
header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "pritidas";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed: " . $conn->connect_error]);
    exit();
}

// Read JSON input
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode([
        "error" => "NO_JSON_RECEIVED",
        "raw" => $raw
    ]);
    exit;
}

/**
 * Normalize date from multiple common formats to YYYY-MM-DD.
 * Returns normalized string on success or false on failure.
 */
function normalize_date($date_str) {
    $date_str = trim($date_str);
    if ($date_str === "") return false;

    // If already in Y-m-d format and valid
    $dt = DateTime::createFromFormat('Y-m-d', $date_str);
    if ($dt && $dt->format('Y-m-d') === $date_str) {
        return $dt->format('Y-m-d');
    }

    // Try several common formats
    $formats = ['d-m-Y', 'd/m/Y', 'm/d/Y', 'Y/m/d', 'd M Y', 'd F Y'];
    foreach ($formats as $fmt) {
        $dt = DateTime::createFromFormat($fmt, $date_str);
        $errors = DateTime::getLastErrors();
        if ($dt && $errors['warning_count'] == 0 && $errors['error_count'] == 0) {
            return $dt->format('Y-m-d');
        }
    }

    // Fallback to strtotime (last resort)
    $ts = strtotime($date_str);
    if ($ts !== false) {
        return date('Y-m-d', $ts);
    }

    return false;
}

// Validate and sanitize inputs
$bill_id        = isset($data['bill_id']) ? (int)$data['bill_id'] : 0;
$amount_paid    = isset($data['amount_paid']) ? (float)$data['amount_paid'] : 0;
$payment_method = isset($data['payment_method']) ? trim($data['payment_method']) : "";
$payment_date   = isset($data['payment_date']) ? trim($data['payment_date']) : date("Y-m-d");
$bank_name      = isset($data['bank_name']) ? trim($data['bank_name']) : "";

// Basic validation
if ($bill_id <= 0) {
    echo json_encode(["error" => "Invalid bill ID"]);
    exit;
}
if ($amount_paid <= 0) {
    echo json_encode(["error" => "Invalid amount paid"]);
    exit;
}
if (empty($payment_method)) {
    echo json_encode(["error" => "Payment method required"]);
    exit;
}

// Normalize payment_date to YYYY-MM-DD
$normalized_date = normalize_date($payment_date);
if ($normalized_date === false) {
    echo json_encode(["error" => "Invalid date format. Use YYYY-MM-DD or DD-MM-YYYY or common formats."]);
    exit;
}
$payment_date = $normalized_date;

// Check if bill exists
$billStmt = $conn->prepare("SELECT total_amount FROM bill WHERE bill_id = ?");
if (!$billStmt) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}
$billStmt->bind_param("i", $bill_id);
$billStmt->execute();
$billResult = $billStmt->get_result();
if ($billResult->num_rows == 0) {
    echo json_encode(["error" => "Bill not found"]);
    $billStmt->close();
    exit;
}
$billRow = $billResult->fetch_assoc();
$total_amount = (float)$billRow['total_amount'];
$billStmt->close();

// Insert payment using prepared statement
$stmt = $conn->prepare("INSERT INTO payment (bill_id, amount_paid, payment_method, payment_date, bank_name) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}
$stmt->bind_param("idsss", $bill_id, $amount_paid, $payment_method, $payment_date, $bank_name);

if ($stmt->execute()) {
    $payment_id = $stmt->insert_id;
    $stmt->close();

    // Calculate total paid
    $sumStmt = $conn->prepare("SELECT COALESCE(SUM(amount_paid),0) AS total_paid FROM payment WHERE bill_id = ?");
    $sumStmt->bind_param("i", $bill_id);
    $sumStmt->execute();
    $sumResult = $sumStmt->get_result()->fetch_assoc();
    $total_paid = (float)$sumResult['total_paid'];
    $sumStmt->close();

    // Calculate remaining
    $remaining_bill = max(0, $total_amount - $total_paid);

    // Update bill
    $updateStmt = $conn->prepare("UPDATE bill SET paid_bill = ?, remaining_bill = ? WHERE bill_id = ?");
    $updateStmt->bind_param("ddi", $total_paid, $remaining_bill, $bill_id);
    $updateStmt->execute();
    $updateStmt->close();

    // Fetch the inserted payment row to return to client
    $fetchStmt = $conn->prepare("SELECT payment_id, bill_id, amount_paid, payment_method, payment_date, bank_name FROM payment WHERE payment_id = ?");
    $fetchStmt->bind_param("i", $payment_id);
    $fetchStmt->execute();
    $paymentRow = $fetchStmt->get_result()->fetch_assoc();
    $fetchStmt->close();

    echo json_encode([
        "success" => true,
        "payment_id" => $payment_id,
        "payment" => $paymentRow,
        "updated_paid_bill" => $total_paid,
        "updated_remaining_bill" => $remaining_bill
    ]);
} else {
    $err = $stmt->error;
    $stmt->close();
    http_response_code(500);
    echo json_encode(["error" => "Insert failed: " . $err]);
}

$conn->close();
?>
