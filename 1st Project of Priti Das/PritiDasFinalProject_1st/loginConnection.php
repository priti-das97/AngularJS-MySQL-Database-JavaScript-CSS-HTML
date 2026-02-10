<?php
session_start();

// ---- DB CONFIG ----
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pritidas"; // your database name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form input
$identifier = trim($_POST['username'] ?? ''); // username OR email
$password   = $_POST['password'] ?? '';

if ($identifier === '' || $password === '') {
    header("Location: login.php?error=Please+enter+both+fields");
    exit();
}

// Look up user by username OR email
$sql = "SELECT username, password, email 
        FROM users 
        WHERE username = ? OR email = ? 
        LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ss", $identifier, $identifier);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Compare plain password (since your DB stores it plain)
    if ($password === $user['password']) {
        $_SESSION['users'] = [
            'username' => $user['username'],
            'email'    => $user['email']
        ];

        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: login.php?error=Invalid+credentials");
        exit();
    }
} else {
    header("Location: login.php?error=User+not+found");
    exit();
}
