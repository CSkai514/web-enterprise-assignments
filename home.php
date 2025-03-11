<?php
session_start();

if (!isset($_SESSION['user_role']))  {
    if (($_SESSION['loggedIn'] != true)){
        header("Location: login.php"); 
    }
    else{
        $_SESSION['user_role'] = 'guest_user';
    }
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
