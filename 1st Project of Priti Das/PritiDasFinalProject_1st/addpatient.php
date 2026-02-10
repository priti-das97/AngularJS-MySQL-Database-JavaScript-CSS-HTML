<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost","root","","pritidas");

$data = json_decode(file_get_contents("php://input"), true);
$name = $conn->real_escape_string($data['p_name']);
$age = $conn->real_escape_string($data['age']);
$gender = $conn->real_escape_string($data['gender']);
$contact = $conn->real_escape_string($data['contact_info']);
$address = $conn->real_escape_string($data['address']);
$doctor = $conn->real_escape_string($data['doctor_id']);
$room = $conn->real_escape_string($data['room_id']);

$sql = "INSERT INTO patient (p_name, age, gender, contact_info, address, doctor_id, room_id) 
        VALUES ('$name','$age','$gender','$contact','$address','$doctor','$room')";
if($conn->query($sql) === TRUE){
    $id = $conn->insert_id;
    echo json_encode([
        "patient_id"=>$id,
        "p_name"=>$name,
        "age"=>$age,
        "gender"=>$gender,
        "contact_info"=>$contact,
        "address"=>$address,
        "doctor_id"=>$doctor,
        "room_id"=>$room
    ]);
}else{
    echo json_encode(["error"=>$conn->error]);
}
$conn->close();
?>
