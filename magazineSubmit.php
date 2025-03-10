<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload magazine</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .upload-container {
            width: 400px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .upload-container input,
        .upload-container textarea,
        .upload-container button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .image-preview {
            width: 100%;
            height: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
        }

        .terms-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .terms-container input {
            margin-right: 5px;
        }
    </style>
</head>
<body>

    <div class="upload-container">
        <h2>Create Magazine</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="article_word" placeholder="Article Content" required></textarea>
            <p>Uploaded Image Preview</p>
            <div class="image-preview" id="imagePreview">No image uploaded to preview now</div>
            <input type="file" name="image" id="imageUpload" accept="image/*" required>
            <button type="submit" name="submit">Submit</button>
            <div class="terms-container">
                <input type="checkbox" required>
                <label>I accept the <a href="#">terms and conditions</a> for the article</label>
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