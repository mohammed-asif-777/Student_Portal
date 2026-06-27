<?php
$host = "localhost";
$user = "root";
$pass = "Theenu786$";
$db = "student_portal";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
