<?php
session_start();
$pdo = require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];

    // Get original article
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch();

    if (!$article) {
        $_SESSION['error_message'] = "Article not found.";
        $_SESSION['show_alert_global'];
        header("Location: home.php");
        exit;
    }

    // Handle updated files
    $wordFilePath = $article['word_file'];
    $imageFilePath = $article['image_file'];
    $uploadDir = "uploads/";

    if (!empty($_FILES['docs']['name'])) {
        $newWord = $uploadDir . uniqid('word_') . '_' . basename($_FILES['docs']['name']);
        if (move_uploaded_file($_FILES['docs']['tmp_name'], $newWord)) {
            $wordFilePath = $newWord;
        }
    }

    if (!empty($_FILES['image']['name'])) {
        $newImage = $uploadDir . uniqid('img_') . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $newImage)) {
            $imageFilePath = $newImage;
        }
    }

    // Update article
    $updated_at = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("UPDATE articles SET title=?, word_file=?, image_file=?, updated_at=? WHERE id=?");
    $stmt->execute([$title, $wordFilePath, $imageFilePath, $updated_at, $id]);

    $_SESSION['alert_message'] = "Article updated successfully.";
    $_SESSION['show_alert_global'];
    header("Location: home.php");
    exit;
}
?>
