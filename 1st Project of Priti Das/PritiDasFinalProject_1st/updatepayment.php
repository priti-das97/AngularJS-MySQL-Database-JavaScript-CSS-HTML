<?php
header('Content-Type: application/json');
$host="localhost"; $user="root"; $pass=""; $db="pritidas";
$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){ die(json_encode(["error"=>"DB failed"])); }

$data = json_decode(file_get_contents("php://input"), true);

$payment_id = (int)$data['payment_id'];
$amount     = (float)$data['amount_paid'];
$method     = $conn->real_escape_string($data['payment_method']);
$date       = $conn->real_escape_string($data['payment_date']);
$bank       = $conn->real_escape_string($data['bank_name']);


// 🔹 Step 1 — Get the bill_id linked to this payment
$res = $conn->query("SELECT bill_id FROM payment WHERE payment_id = $payment_id");
if($res->num_rows == 0){
    die(json_encode(["error" => "Payment not found"]));
}
$row = $res->fetch_assoc();
$bill_id = (int)$row['bill_id'];


// 🔹 Step 2 — Update the payment itself
$update = $conn->prepare("
    UPDATE payment 
    SET amount_paid = ?, payment_method = ?, payment_date = ?, bank_name = ?
    WHERE payment_id = ?
");
$update->bind_param("dsssi", $amount, $method, $date, $bank, $payment_id);
$update_success = $update->execute();
$update->close();

if(!$update_success){
    echo json_encode(["error" => "Failed to update payment"]);
    exit;
}


// 🔹 Step 3 — Recalculate total paid for that bill
$res2 = $conn->prepare("SELECT COALESCE(SUM(amount_paid), 0) FROM payment WHERE bill_id = ?");
$res2->bind_param("i", $bill_id);
$res2->execute();
$res2->bind_result($total_paid);
$res2->fetch();
$res2->close();


// 🔹 Step 4 — Get total bill amount
$res3 = $conn->prepare("SELECT total_amount FROM bill WHERE bill_id = ?");
$res3->bind_param("i", $bill_id);
$res3->execute();
$res3->bind_result($total_amount);
$res3->fetch();
$res3->close();

$remaining = max(0, $total_amount - $total_paid);


// 🔹 Step 5 — Update bill table
$updBill = $conn->prepare("UPDATE bill SET paid_bill = ?, remaining_bill = ? WHERE bill_id = ?");
$updBill->bind_param("ddi", $total_paid, $remaining, $bill_id);
$updBill->execute();
$updBill->close();


// 🔹 Final response
echo json_encode([
    "success" => true,
    "bill_updated" => [
        "bill_id" => $bill_id,
        "paid_bill" => $total_paid,
        "remaining_bill" => $remaining
    ]
]);

$conn->close();
?>
