<?php
/* =========================
   DATABASE CONNECTION FILE
   ========================= */

$host = "localhost";   // XAMPP MySQL host
$user = "root";        // default XAMPP username
$pass = "";            // default XAMPP password (empty)
$db   = "pritidas";    // 🔴 YOUR DATABASE NAME (from phpMyAdmin)

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
