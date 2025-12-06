<?php
// db.php
$servername = "localhost";
$username = "";
$password = "";
$database = "zarinpay";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
