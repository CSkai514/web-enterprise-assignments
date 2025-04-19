<?php
session_start();
$pdo = require 'db_connect.php';
include 'required_login.php'; 
if (($_SESSION['loggedIn'] != true)){
    $_SESSION['alert_message'] = "Please log in to view the Home page.";
    header("Location: login.php"); 
}
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

    $start_and_closure_date_dataFromdatabase = $pdo->prepare("SELECT open_date, close_date FROM magazine_closure_settings ORDER BY created_at DESC LIMIT 1");
    $start_and_closure_date_dataFromdatabase->execute();
    $start_and_closure_date_dataFromdatabase_Data = $start_and_closure_date_dataFromdatabase->fetch();
    
    if ($start_and_closure_date_dataFromdatabase_Data) {
        $openDate = $start_and_closure_date_dataFromdatabase_Data['open_date']; 
        $closeDate = $start_and_closure_date_dataFromdatabase_Data['close_date']; 
    
        $currentDate = date('Y-m-d');
        if ($currentDate < $openDate) {
            $_SESSION['error_message'] = "The submission period has not yet started.";
            header("Location: magazineSubmit.php");
            exit;
        } elseif ($currentDate > $closeDate) {
            $_SESSION['error_message'] = "The submission period has closed.";
            header("Location: magazineSubmit.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Unable to retrieve submission dates.";
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
    $allowedImages = ['image/jpeg', 'image/png'];

    if (!in_array($wordFile['type'], $allowedDocs)) {
        $_SESSION['error_message'] = "Only .doc, .docx, or .pdf files are allowed for Word files.";
        header("Location: magazineSubmit.php");
        exit;
    }

    if (!in_array($imageFile['type'], $allowedImages)) {
        $_SESSION['error_message'] = "Only JPEG or PNG images are allowed.";
        header("Location: magazineSubmit.php");
        exit;
    }

    if (move_uploaded_file($wordFile['tmp_name'], $wordPath) && move_uploaded_file($imageFile['tmp_name'], $imagePath)) {
        $created_at = date('Y-m-d H:i:s');
        $updated_at = $created_at;

        try {
            $comment_deadline = date('Y-m-d H:i:s', strtotime('+14 days'));
            $stmt = $pdo->prepare("INSERT INTO articles (user_id, faculty_id, title, word_file, image_file, agreed_terms, is_selected, created_at, updated_at, description,comment_deadline) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
            $is_selected = 0;
            $stmt->execute([$user_id, $faculty_id, $title, $wordPath, $imagePath, $agreed_terms, $is_selected, $created_at, $updated_at, $article_word,$comment_deadline]);
            $coordinatorStmt = $pdo->prepare("SELECT email, name     FROM users WHERE role = 'marketingcoordinator' AND faculty_id = ?");
            $coordinatorStmt->execute([$faculty_id]);
            $coordinator = $coordinatorStmt->fetch(PDO::FETCH_ASSOC);
        
            if ($coordinator) {
                $marketingCoordinatorEmail = $coordinator['email'];
                $marketingCoordinator = $coordinator['name'] ?? 'MarketingCoordinator';
        
                $subject = "New Article Submission - Action Required";
        
                $message = "
                <html>
                <head>
                <title>New Article Submitted</title>
                </head>
                <body>
                <p>Dear {$marketingCoordinator},</p>
                <p>A new article has been submitted and requires your attention.</p>
                <p><strong>Title:</strong> {$title}</p>
                <p><strong>Description:</strong> {$article_word}</p>
                <p><strong>Comment Deadline:</strong> {$comment_deadline}</p>
                <p>Please log in to the system and provide your comment within 14 days.</p>
                <br>
                <p>Thank you.</p>
                </body>
                </html>
                ";
        
                mail($marketingCoordinatorEmail, $subject, $message);
            }   
            $_SESSION['alert_message'] = "Magazine article submitted successfully!, Email sent to Marketing Coordinator's Email: " . $marketingCoordinatorEmail;
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