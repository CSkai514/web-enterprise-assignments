<?php
session_start();
$pdo = require 'db_connect.php';

$id = $_GET['id'] ?? 0;

$article = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$article->execute([$id]);
$data = $article->fetch();

if (!$data) {
    echo "Article not found";
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$faculty_id = $_SESSION['faculty_id'];

if (
    $role === 'student' && $data['user_id'] != $user_id ||
    $role === 'coordinator' && $data['faculty_id'] != $faculty_id
) {
    echo "Not authorized, you don't have permission to view this article.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Maganize</title>
    <style>
        :root {
            --mainTheme: #ffebcc;
            --subTheme: #ffcc80;
            --contrastTheme: #ffa64d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--mainTheme);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .view-container {
            width: 400px;
            padding: 20px;
            background: var(--contrastTheme);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: white;
        }

        .view-container input,
        .view-container textarea,
        .view-container button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ffffff;
            border-radius: 5px;
            background-color: #d3d3d3;
            color: #333;
            cursor: not-allowed;
        }

        .image-preview {
            width: 100%;
            height: 200px;
            border: 1px solid #ffffff;
            border-radius: 5px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d3d3d3;
        }

        #back-btn {
            background-color: var(--subTheme);
            color: black;
            font-size: 20px;
            text-decoration: none;
            display: block;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .view-container label {
            font-weight: bold;
            margin-top: 10px;
        }

        .view-container input[readonly] {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<div class="view-container">
    <h2>View Maganize</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" readonly><br>
        <label>Description:</label>
        <textarea name="article_word" readonly><?= htmlspecialchars($data['description']) ?></textarea><br>
        <label>Word File:</label>
        <input type="text" value="<?= basename($data['word_file']) ?>" readonly><br>

        <label>Image:</label>
        <div class="image-preview" style="background-image: url('<?= $data['image_file'] ?>');">
            <span>Image Preview</span>
        </div><br>

        <a href="maganize_view.php" id="back-btn">Back</a>
    </form>
</div>

</body>
</html>
