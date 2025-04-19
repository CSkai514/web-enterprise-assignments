<?php 
    if (($_SESSION['loggedIn'] != true)){
            $_SESSION['alert_message'] = "Please log in to view the Home page.";
            header("Location: login.php"); 
        }
?>