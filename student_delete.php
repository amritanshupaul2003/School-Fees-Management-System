<?php
session_start();
include 'config.php';
if (!isset($_SESSION['username'])) { header("Location: login.html"); exit(); }

$id = $_GET['id'];
$conn->query("DELETE FROM students WHERE student_id='$id'");
header("Location: students.php");
exit();
