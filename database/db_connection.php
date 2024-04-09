<?php
$servername = "192.168.192.203";
$username = "root";
$password = "";
$dbname = "vira";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>