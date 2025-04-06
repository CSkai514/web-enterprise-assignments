<?php 
  $title_header = "The login page";
  session_start();
  include 'librarycdn.php';
 if (isset($_POST['guestUser_login'])) {
     $_SESSION['user_role'] = 'guest_user';
     $_SESSION['loggedIn'] = true;
     header("Location: home.php"); 
     exit();
 }
//  $plainPassword = 'PasswordTest1';

// // Hash the password
// $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// // Output the hashed password to copy
// echo $hashedPassword;
 ?>
<?php include 'header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
   
    <link rel="stylesheet" href="login-styles.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form method="post" action="loginAuthentication.php"> 
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                <button class="mt-3" type="submit">Login</button>
                </form>
                <form method="post" action="">
                    <button class="mt-3 guest-login" type="submit" name="guestUser_login">Login as Guest</button>
                </form>
        </div>
    </div>
    <script>
        <?php if (isset($_SESSION['alert_message'])): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Request to page denied',
                text: '<?php echo $_SESSION['alert_message']; ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['alert_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>