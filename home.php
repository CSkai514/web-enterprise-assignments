<?php
session_start();
    if (($_SESSION['loggedIn'] != true)){
        $_SESSION['alert_message'] = "Please log in to view the Home page.";
        header("Location: login.php"); 
    }
    $role = $_SESSION['role'];
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

    <?php if ($role === 'student' || $role === 'admin'): ?>
        <a href="magazineSubmit.php"><p>Add New Magazine</p></a>
    <?php endif; ?>

    <?php if ($role === 'coordinator' || $role === 'admin'): ?>
        <a href="coordinator_maganize_settings.php"><p>Add Closure Date</p></a>
    <?php endif; ?>

    <a href="#"><p>Option 4</p></a> <!-- Optional: visible to all -->
</aside>
        <header class="topbar">
        
            <div class="avatar">Avatar</div><br>
            <span style="padding-left: 20px;">Role: <?= ucfirst($_SESSION['role']) ?></span>
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
