<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}
echo "Welcome, Manager " . htmlspecialchars($_SESSION['email']) . "!";
?>