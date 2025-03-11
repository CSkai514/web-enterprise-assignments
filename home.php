<?php
session_start();
    if (($_SESSION['loggedIn'] != true)){
        $_SESSION['alert_message'] = "Please log in to view the Home page.";
        header("Location: login.php"); 
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <p class="side-menu-title">Side menu</p>
            <a href="#"><p>Dashboard</p></a>
            <a href="magazineSubmit.php"><p>Submit maganize</p></a>
            <a href="#"><p>Option 3</p></a>
            <a href="#"><p>Option 4</p></a>
        </aside>
        <header class="topbar">
          
            <div class="avatar">Avatar</div>
            <a href="logout.php" style="padding-left: 20px;">Logout</a>
        </header>
        <main class="content">
            <div class="cards">
                <div class="card"></div>
                <div class="card"></div>
                <div class="card"></div>
            </div>
        </main>
    </div>




</body>
</html>
