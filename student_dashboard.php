<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}
echo "Welcome, Student " . htmlspecialchars($_SESSION['email']) . "!<br>";
echo "Faculty ID: " . ($_SESSION['faculty_id'] ?? 'N/A');
?>