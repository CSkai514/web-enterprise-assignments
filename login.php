<?php 
  $title_header = "The login page";
  session_start();
 
 if (isset($_POST['guestUser_login'])) {
     $_SESSION['user_role'] = 'guest_user';
     $_SESSION['loggedIn'] = true;
     header("Location: home.php"); 
     exit();
 }
 
 ?>
 
<?php include 'header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="login-styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email address</label>
              </div>
              <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
              </div>
              <button class="mt-3" type="submit">Login</button>
              <form method="post" action="login.php"> 
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