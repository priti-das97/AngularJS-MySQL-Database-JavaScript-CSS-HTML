<?php
header('Content-Type: application/json');
require 'db_connect.php';

$data = [];

/* ======================
   TOP CARDS
====================== */
$data['totalDoctors'] = (int)$conn->query(
  "SELECT COUNT(*) FROM doctor"
)->fetch_row()[0];

$data['totalPatients'] = (int)$conn->query(
  "SELECT COUNT(*) FROM patient"
)->fetch_row()[0];

$data['roomsAvailable'] = (int)$conn->query(
  "SELECT COUNT(*) FROM room WHERE is_available = 1"
)->fetch_row()[0];

$data['roomsTotal'] = (int)$conn->query(
  "SELECT COUNT(*) FROM room"
)->fetch_row()[0];

$data['pendingBills'] = (int)$conn->query(
  "SELECT COUNT(*) FROM bill WHERE remaining_bill > 0"
)->fetch_row()[0];

$data['paidAmount'] = (float)$conn->query(
  "SELECT COALESCE(SUM(paid_bill),0) FROM bill"
)->fetch_row()[0];

$data['totalRemaining'] = (float)$conn->query(
  "SELECT COALESCE(SUM(remaining_bill),0) FROM bill"
)->fetch_row()[0];

/* ======================
   PIE CHART (GENDER)
====================== */
$genderQ = $conn->query("
  SELECT gender, COUNT(*) total
  FROM patient
  GROUP BY gender
");

$data['gender'] = ['Male'=>0,'Female'=>0];
while ($g = $genderQ->fetch_assoc()) {
  $data['gender'][$g['gender']] = (int)$g['total'];
}

/* ======================
   LINE CHART (BILLS)
====================== */
$billQ = $conn->query("
  SELECT bill_id,
         paid_bill,
         remaining_bill
  FROM bill
  ORDER BY bill_id
");

$data['billing'] = [];
while ($b = $billQ->fetch_assoc()) {
  $data['billing'][] = $b;
}

echo json_encode($data);
