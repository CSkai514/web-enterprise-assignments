<?php
session_start();
include 'required_login.php'; 
$pdo = require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Fetch file paths before deletion
    $stmt = $pdo->prepare("SELECT image_file, word_file FROM articles WHERE id = ?");
    $stmt->execute([$delete_id]);
    $files = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete record from DB
    $deleteQuery = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $deleteQuery->execute([$delete_id]);

    // Delete files from server
    if ($files) {
        if (!empty($files['image_file']) && file_exists($files['image_file'])) {
            unlink($files['image_file']);
        }
        if (!empty($files['word_file']) && file_exists($files['word_file'])) {
            unlink($files['word_file']);
        }
    }

    $_SESSION['alert_message'] = "Article deleted successfully.";
    $_SESSION['show_alert'] = true;

    header("Location: maganize_view.php");
    exit();
} else {

    header("Location: maganize_view.php");
    exit();
}