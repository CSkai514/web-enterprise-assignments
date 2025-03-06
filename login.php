<?php 
 $title_header = "the login page" 
 ?>
<?php include 'header.php';?>
<style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color:rgb(0, 82, 170);
        }
</style>
<body>
<div class="login-container">
        <h2>Login</h2>
        <form action="#" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" >
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" >
            
            <a href="#">
                <button type="button">Login</button>
            </a>
            <hr>
            <a href="home.php">
                <button type="button">login as Guest</button>
            </a>
        </form>
    </div>
</body>
</html>