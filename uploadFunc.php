
<?php
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $article_word = $_POST['article_word'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . time() . "_" . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = ["jpg", "jpeg", "png",];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Only JPG, JPEG, PNG files are allowed.");
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "<h2>Upload Successful!</h2>";
            echo "<p>Title: $title</p>";
            echo "<p>Article: $article_word</p>";
            echo "<p>Image:</p><img src='$target_file' width='300'>";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No image uploaded.";
    }
}
?>