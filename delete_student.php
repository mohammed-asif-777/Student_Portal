<?php
session_start();
include "db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); exit();
}
if (!isset($_GET['rrn'])) {
    header("Location: admin_dashboard.php"); exit();
}
$rrn = $conn->real_escape_string($_GET['rrn']);
$conn->query("DELETE FROM students WHERE rrn='$rrn'");
header("Location: admin_dashboard.php");
exit();
?>
