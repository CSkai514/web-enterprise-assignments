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
    echo "Not authorized, don't have permission to";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Maganize</title>
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

        .upload-container {
            width: 400px;
            padding: 20px;
            background: var(--contrastTheme);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: white;
        }

        .upload-container input,
        .upload-container textarea,
        .upload-container button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ffffff;
            border-radius: 5px;
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

        #submit-btn {
            background-color: var(--subTheme);
            color: black;
            font-size: 20px;
        }

        #cancel-btn {
            background-color: var(--subTheme);
            color: black;
            font-size: 20px;
        }

        .terms-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .terms-container input {
            margin-right: 5px;
        }

        .checkbox {
            width: auto !important;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: white;
        }
    </style>
</head>
<body>

<div class="upload-container">
    <h2>Update Maganize</h2>

    <form method="POST" action="update_maganize.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" required><br>
        <label>Description:</label>
        <textarea name="article_word" required><?= htmlspecialchars($data['description']) ?></textarea><br>
        <label>Replace Word File:</label>
        <input type="file" name="docs"><br>

        <label>Replace Image:</label>
        <input type="file" name="image"><br>

        <button type="submit" name="update" id="submit-btn">Update</button>
        <a href="maganize_view.php" id="cancel-btn" style="display:block; text-align:center; text-decoration:none; padding:10px; border-radius:5px;">Cancel</a>

        <div class="terms-container">
            <input type="checkbox" class="checkbox" name="terms" required>
            <label>I accept the <a href="#">terms and conditions</a> for the article</label>
        </div>
    </form>
</div>

</body>
</html>
