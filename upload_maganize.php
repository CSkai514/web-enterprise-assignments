<?php
session_start();
$pdo = require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {


    $user_id = $_SESSION['user_id'] ?? 0;
    $faculty_id = $_SESSION['faculty_id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $article_word = $_POST['article_word'] ?? '';
    $agreed_terms = isset($_POST['terms']) ? 1 : 0;

    

    if (!$user_id || !$title || !$article_word || !$agreed_terms) {
        $_SESSION['error_message'] = "Missing required fields or terms not agreed.";
        
        header("Location: magazineSubmit.php");
        exit;
    }

    
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $wordFile = $_FILES['docs'];
    $imageFile = $_FILES['image'];

    $wordFileName = basename($wordFile['name']);
    $imageFileName = basename($imageFile['name']);

    $wordPath = $uploadDir . uniqid('word_') . '_' . $wordFileName;
    $imagePath = $uploadDir . uniqid('img_') . '_' . $imageFileName;


    $allowedDocs = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf'];
    $allowedImages = ['image/jpeg', 'image/png', 'image/gif'];


    if (!in_array($wordFile['type'], $allowedDocs)) {
        $_SESSION['error_message'] = "Only .doc, .docx, or .pdf files are allowed for Word files.";
        header("Location: magazineSubmit.php");
        exit;
    }

    if (!in_array($imageFile['type'], $allowedImages)) {
        $_SESSION['error_message'] = "Only JPEG, PNG, or GIF images are allowed.";
        header("Location: magazineSubmit.php");
        exit;
    }

    if (move_uploaded_file($wordFile['tmp_name'], $wordPath) && move_uploaded_file($imageFile['tmp_name'], $imagePath)) {
        $created_at = date('Y-m-d H:i:s');
        $updated_at = $created_at;

        try {
            $stmt = $pdo->prepare("INSERT INTO articles (user_id, faculty_id, title, word_file, image_file, agreed_terms, is_selected, created_at, updated_at) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            
            $is_selected = 0;
            $stmt->execute([$user_id, $faculty_id, $title, $wordPath, $imagePath, $agreed_terms, $is_selected, $created_at, $updated_at]);

            $_SESSION['alert_message'] = "Article submitted successfully!";
            $_SESSION['show_alert'] = true; 
            header("Location: magazineSubmit.php"); 
            exit;
        
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
            header("Location: magazineSubmit.php");
            exit;
        }

    } else {
        $_SESSION['error_message'] = "Failed to upload files.";
        header("Location: magazineSubmit.php");
        exit;
    }

} else {
    header("Location: magazineSubmit.php");
    exit;
}
?>