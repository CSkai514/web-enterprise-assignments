<?php
session_start();
include 'required_login.php'; 
$pdo = require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = $_POST['article_id'];
    
    $comment = $_POST['comment'];
    $coordinatorId = $_SESSION['user_id'];
    $checkQuery = $pdo->prepare("SELECT id FROM article_comments WHERE article_id = ? AND coordinator_id = ?");
    $checkQuery->execute([$articleId, $coordinatorId]);
    $existingComment = $checkQuery->fetch();

    if ($existingComment) {
        $updateQuery = $pdo->prepare("UPDATE article_comments SET comment = ?, created_at = NOW() WHERE id = ?");
        $updateQuery->execute([$comment, $existingComment['id']]);
        $response = [
            'success' => true,
            'message' => 'Comment updated successfully.'
        ];
        echo json_encode(['success' => true, 'message' => 'Comment updated successfully.']);
    } else {
        $insertQuery = $pdo->prepare("INSERT INTO article_comments (article_id, coordinator_id, comment, created_at) VALUES (?, ?, ?, NOW())");
        $insertQuery->execute([$articleId, $coordinatorId, $comment]);
        $response = [
            'success' => true,
            'message' => 'Comment added successfully.'
        ];
        echo json_encode(['success' => true, 'message' => 'Comment saved successfully.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
exit;
