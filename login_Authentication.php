<?php
session_start();
require_once 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, email, password_hash, role, faculty_id FROM user WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['faculty_id'] = $user['faculty_id'];

        switch ($user['role']) {
            case 'student':
                header("Location: student_dashboard.php");
                exit;
            case 'coordinator':
                // Check if user is in marketingcoordinator table
                $stmt = $pdo->prepare("SELECT user_id FROM marketingcoordinator WHERE user_id = :id");
                $stmt->execute(['id' => $user['id']]);
                if ($stmt->fetch()) {
                    header("Location: coordinator_dashboard.php");
                    exit;
                }
                break;
            case 'manager':
                // Check if user is in marketingmanager table
                $stmt = $pdo->prepare("SELECT user_id FROM marketingmanager WHERE user_id = :id");
                $stmt->execute(['id' => $user['id']]);
                if ($stmt->fetch()) {
                    header("Location: manager_dashboard.php");
                    exit;
                }
                break;
            case 'admin':
                // Check if user is in admin table
                $stmt = $pdo->prepare("SELECT user_id FROM admin WHERE user_id = :id");
                $stmt->execute(['id' => $user['id']]);
                if ($stmt->fetch()) {
                    header("Location: admin_dashboard.php");
                    exit;
                }
                break;
            default:
                $error = "Invalid role assigned.";
                break;
        }
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        $error = "Invalid email or password.";
        header("Location: login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University Magazine</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>