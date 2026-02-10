<?php
header('Content-Type: application/json');
$host="localhost"; $user="root"; $pass=""; $db="pritidas";
$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){ die(json_encode(["error"=>"DB failed"])); }

$data = json_decode(file_get_contents("php://input"), true);
$id = (int)$data['payment_id'];
$sql = "DELETE FROM payment WHERE payment_id='$id'";
if($conn->query($sql)){
  echo json_encode(["success"=>true]);
}else{
  echo json_encode(["error"=>$conn->error]);
}
$conn->close();
?>
