<?php
session_start();
include 'librarycdn.php'; 

$pdo = require 'db_connect.php';

if (isset($_POST['select_id']) && isset($_POST['action'])) {
    $selected_id = $_POST['select_id'];
    $action = $_POST['action'];
    
    if ($action === 'select') {
        $updateQuery = $pdo->prepare("UPDATE articles SET is_selected = 1 WHERE id = ?");
        $updateQuery->execute([$selected_id]);
    } elseif ($action === 'deselect') {
        $updateQuery = $pdo->prepare("UPDATE articles SET is_selected = 0 WHERE id = ?");
        $updateQuery->execute([$selected_id]);
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
