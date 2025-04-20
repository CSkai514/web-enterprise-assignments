<?php
session_start();
include 'librarycdn.php';
$dataCheckFromdatabase = require 'db_connect.php';

$closureQuery = $dataCheckFromdatabase->query("SELECT open_date, close_date, final_closure_date FROM magazine_closure_settings ORDER BY id DESC LIMIT 1");
$closureData = $closureQuery->fetch(PDO::FETCH_ASSOC);

$closeDate = $closureData['close_date'] ?? null;
$faculty_id = $_SESSION['faculty_id'];
    if (($_SESSION['loggedIn'] != true)){
        $_SESSION['alert_message'] = "Please log in to view the Submission page.";
        header("Location: login.php"); 
    }
    if (isset($_SESSION['error_message'])) {
        $errorMsg = $_SESSION['error_message'];
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: " . json_encode($errorMsg) . ",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>";
        echo  $faculty_id ?? null;
        unset($_SESSION['error_message']);
    }
    if (isset($_SESSION['show_alert']) && $_SESSION['show_alert']) {
        $alertMessage = addslashes($_SESSION['alert_message']);
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                      title: '$alertMessage',
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'home.php'; // Redirect to home.php after confirming
                    }
                });
            });
        </script>";
        unset($_SESSION['show_alert']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Maganize</title>
    <style>
        :root{
    --mainTheme: #ffebcc;
    --subTheme: #ffcc80;
    --contrastTheme: #ffa64d;
}

*{
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: var(--mainTheme); /* Light orange */
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

        #submit-btn{
            background-color: var(--subTheme);
            color: black;
            font-size: 20px;
        }
        #cancel-btn{
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

        .checkbox{
            width:auto !important; 
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
        <h2>Create Maganize</h2>
        <?php if ($closeDate): ?>
            <div style="margin-bottom: 15px; color: white; background-color: rgba(0,0,0,0.2); padding: 10px; border-radius: 5px;">
                <strong>Submission Period:</strong><br>
                Close submission Date: <?= date("d M Y", strtotime($closeDate)) ?>
            </div>
        <?php endif; ?>
        <form action="upload_maganize.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="article_word" placeholder="Description" required></textarea>
            <p>Uploaded Image Preview</p>
            <div class="image-preview" id="imagePreview">No image uploaded to preview now</div>
            <p>Image upload</p>
            <input type="file" name="image" id="imageUpload" accept="image/*" required>
            <p>Uploaded Words file for your article contents</p>
            <input type="file" name="docs" id="docsUpload" accept=".doc,.docx,.pdf" required>
            <button type="submit" name="submit" id="submit-btn">Submit</button>
            <a href="home.php" id="cancel-btn" style="display:block; text-align:center; text-decoration:none; padding:10px; border-radius:5px;">Cancel</a>

            <!-- <div class="terms-container">
                <input type="checkbox" class="checkbox" required>
                <label>I accept the <a href="#">terms and conditions</a> for the article</label>
            </div> -->
            <div class="terms-container">
                <input type="checkbox" class="checkbox" name="terms" required>
                <label>I accept the <a href="termsNconditions.html">terms and conditions</a> for the article</label>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("imageUpload").addEventListener("change", function(event) {
            let image_reader = new FileReader();
            image_reader.onload = function() {
                let output = document.getElementById("imagePreview");
                output.style.backgroundImage = "url(" + image_reader.result + ")";
                output.textContent = "";
            };
            image_reader.readAsDataURL(event.target.files[0]);
        });
    </script>

</body>
</html>